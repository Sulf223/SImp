# Prompt Gemini CLI – Bug-uri logice runda 8 (7 fix-uri)

Rulezi din `C:\wamp64\www\SImp`:

```powershell
gemini "$(Get-Content -Raw .\PROMPT_GEMINI_LOGIC_R8.md)"
```

---

## PROMPT (copiază tot)

Ești inginer software senior. Lucrezi în directorul curent. Aplicația SImp Portal e în `site_g/`. A trecut prin 7 runde de audit. Acum aplici 7 fix-uri pentru bug-uri LOGICE descoperite în runda 8. La final scrii `LOGIC_R8_REPORT.md`.

### Reguli generale (NU le încălca)

- NU schimba semnătura funcțiilor PHP existente.
- Folosește exclusiv prepared statements cu `bind_param`.
- Comentează modificările cu `// FIX [L-ID]:`.
- Validează `php -l` la final pe fișierele PHP modificate.
- Nu introduce dependințe noi.
- Citește scurt rapoartele anterioare ca să nu strici fix-urile aplicate (`AUDIT_R7_REPORT.md` etc.).

---

## [L1] HIGH — Reset progress nu șterge user_achievements

**Fișier:** `site_g/PHP/admin_actions.php`, în blocul `if ($action === 'reset_progress')`.

**Problemă:** după reset, utilizatorul are 0 grile rezolvate dar achievements rămân deblocate → inconsistență.

**Fix:** în array-ul `$tabele_progres`, adaugă o linie nouă:
```php
"DELETE FROM user_achievements WHERE user_id = ?",
```

Plasează imediat după `"DELETE FROM activity_day WHERE user_id = ?"` și înainte de `"UPDATE user_streak ..."`. Comentariu: `// FIX [L1]: șterge și achievements pentru consistență cu progresul resetat`.

---

## [L2] HIGH — Rate-limit la login pe (IP+username), nu doar pe IP

**Fișier:** `site_g/PHP/login_post.php`, liniile cu `check_rate_limit` și `reset_rate_limit`.

**Problemă:** într-o sală de calculator (același IP), un student care greșește parola de 5 ori blochează login pentru toți colegii.

**Fix:** înlocuiește `$user_ip` ca identifier cu un hash compus (IP + username):

Caută:
```php
$user_ip = $_SERVER['REMOTE_ADDR'] ?: 'unknown';
if (!check_rate_limit($con, 'login', $user_ip, 5, 900)) {
```

Înlocuiește cu:
```php
$user_ip = $_SERVER['REMOTE_ADDR'] ?: 'unknown';
// FIX [L2]: rate-limit pe (IP+username) ca să nu blocăm utilizatori inocenți de pe același IP
$rl_identifier = hash('sha256', $user_ip . ':' . strtolower($username));
if (!check_rate_limit($con, 'login', $rl_identifier, 5, 900)) {
```

La fel pentru `reset_rate_limit($con, 'login', $user_ip)` din blocul de success — înlocuiește cu `$rl_identifier`.

---

## [L3] HIGH — Reset password: token-uri concurente valide

**Fișier:** `site_g/PHP/forgot_password_post.php`, înainte de `INSERT INTO password_reset_tokens`.

**Problemă:** dacă utilizatorul cere reset de 2 ori, ambele token-uri rămân valide. Atacator cu primul token poate schimba parola DUPĂ ce victima și-a făcut reset.

**Fix:** înainte de INSERT-ul tokenului nou, marchează celelalte token-uri active ale utilizatorului ca folosite. Caută blocul:
```php
if ($row = $res->fetch_assoc()) {
    $user_id = (int)$row['id'];

    // Generare token
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);

    // Inserare token în DB
    $sql_token = "INSERT INTO password_reset_tokens ...";
```

Înlocuiește prin a adăuga ÎNAINTE de `Inserare token`:
```php
    // FIX [L3]: invalidează token-urile vechi neutilizate ale acestui user
    if ($stmt_invalidate = $con->prepare("UPDATE password_reset_tokens SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL")) {
        $stmt_invalidate->bind_param('i', $user_id);
        $stmt_invalidate->execute();
        $stmt_invalidate->close();
    }
```

---

## [L4] HIGH — Change role nu invalidează sesiunile active

**Fișiere:**
- `site_g/database/upgrade_role_versioning.sql` (nou)
- `site_g/PHP/admin_actions.php`
- `site_g/PHP/auth.php` sau `helpers.php`

**Problemă:** admin schimbă rolul. Userul cu sesiunea activă păstrează rolul vechi 30 min.

**Fix:**

1. Creează `site_g/database/upgrade_role_versioning.sql`:
```sql
ALTER TABLE utilizatori ADD COLUMN role_changed_at DATETIME NULL DEFAULT NULL AFTER rol;
```

2. În `admin_actions.php`, blocul `change_role`, după UPDATE rol cu succes adaugă:
```php
// FIX [L4]: marchează schimbarea pentru a invalida sesiunile vechi
if ($s = $con->prepare("UPDATE utilizatori SET role_changed_at = NOW() WHERE id = ?")) {
    $s->bind_param('i', $user_id);
    $s->execute();
    $s->close();
}
```

3. În `login_post.php` la success login (după `regenerate_session()` și populare `$_SESSION`), adaugă:
```php
$_SESSION['login_at'] = time();
```

4. În `auth.php`, după `enforce_session_timeout` adaugă o funcție nouă apelată din `is_logged_in()` sau ca middleware:
```php
function check_role_consistency(mysqli $con): void {
    if (empty($_SESSION['user_id']) || empty($_SESSION['login_at'])) return;
    if ($s = $con->prepare("SELECT rol, UNIX_TIMESTAMP(role_changed_at) AS changed_ts FROM utilizatori WHERE id = ?")) {
        $s->bind_param('i', $_SESSION['user_id']);
        $s->execute();
        $r = $s->get_result();
        if ($row = $r->fetch_assoc()) {
            $changed = (int)($row['changed_ts'] ?? 0);
            if ($changed > 0 && $changed > (int)$_SESSION['login_at']) {
                // Rolul s-a schimbat după ce utilizatorul s-a logat → forțează re-login
                session_unset();
                session_destroy();
                header('Location: index.php?page=login&expired=1&reason=role_changed');
                exit;
            }
        }
        $s->close();
    }
}
```

5. În `index.php`, după `require_once 'PHP/helpers.php';` și după `session_start`, dacă există `$_SESSION['user_id']`, apelează `check_role_consistency($con)`. Atenție: `$con` trebuie să fie disponibil — include `conexiune.php` în index.php DOAR dacă utilizatorul e logat (nu rupe paginile publice).

---

## [L5] HIGH — Streak timezone server vs user

**Fișier:** `site_g/PHP/helpers.php` (sau direct în `index.php` la început dacă preferi global).

**Problemă:** `date('Y-m-d')` folosește timezone-ul server-ului (de obicei UTC pe Linux). Pentru utilizatorii din România, ziua se schimbă la ora server-ului, nu la 00:00 local.

**Fix simplu (proiect academic România):** la începutul `helpers.php`, după `<?php` și înaintea oricărui cod, adaugă:
```php
// FIX [L5]: Forțăm timezone România pentru calculele de streak și activitate
date_default_timezone_set('Europe/Bucharest');
```

(Dacă vrei multi-timezone, ar trebui coloană în `utilizatori` cu timezone-ul preferat. Pentru proiect academic local, fix-ul de mai sus e suficient.)

---

## [L6] HIGH — Procentaj > 100% în admin panel

**Fișier:** `site_g/pagini/admin.php`, în tab-ul Utilizatori unde se calculează `$procent_grile`.

**Problemă:** dacă admin șterge grile din DB după ce utilizatorul le-a rezolvat, procentul depășește 100% și bara iese din container.

**Fix:** caută:
```php
$procent_grile = $total_grile_disponibile > 0 ? round(((int)$u['grile'] / $total_grile_disponibile) * 100) : 0;
```

Înlocuiește cu:
```php
// FIX [L6]: cap la 100% — în caz că există grile rezolvate care au fost ulterior șterse din DB
$procent_grile = $total_grile_disponibile > 0 ? min(100, round(((int)$u['grile'] / $total_grile_disponibile) * 100)) : 0;
```

Aplică același fix în orice alt loc unde calculezi `($x / $total) * 100` (ex: tab-ul detalii cu progres per algoritm — caută `$row['procent']` și aplică `min(100, ...)`).

---

## [L7] MEDIUM — AI feedback rate-limit race condition

**Fișier:** `site_g/PHP/helpers.php`, funcția `check_rate_limit`.

**Problemă:** 2 cereri concurente pe același endpoint pot trece ambele check-ul înainte ca una să incrementeze contorul.

**Fix:** wrap operațiile critice într-o tranzacție cu `SELECT ... FOR UPDATE` ca să serializeze accesele. Caută:

```php
$sql = "SELECT id, attempt_count, window_start FROM rate_limit_attempts WHERE identifier = ? AND action = ?";
```

Înlocuiește cu:
```php
// FIX [L7]: SELECT ... FOR UPDATE ca să serializăm cererile concurente
mysqli_begin_transaction($con);
try {
    $sql = "SELECT id, attempt_count, window_start FROM rate_limit_attempts WHERE identifier = ? AND action = ? FOR UPDATE";
```

La sfârșitul funcției, după ce inserezi/update-uiezi:
```php
    mysqli_commit($con);
    return $allowed;
} catch (Throwable $e) {
    mysqli_rollback($con);
    error_log('check_rate_limit: ' . $e->getMessage());
    return true; // fail-open ca să nu blocăm utilizatorii la eroare
}
```

(Dacă funcția folosește OOP `$con->prepare()`, înlocuiește cu `$con->begin_transaction()` și `$con->commit()` / `$con->rollback()`.)

---

## OUTPUT FINAL

`LOGIC_R8_REPORT.md` în rădăcina proiectului cu:
- Tabel: ID | Sev | Status (✅ aplicat / ⏳ skip cu motiv) | Fișier(e) | Linii
- Lista comenzilor SQL manuale care trebuie rulate:
  - `site_g/database/upgrade_role_versioning.sql`
- Pași de testare manuală:
  - L1: ca admin, resetează progresul unui user și confirmă că achievements dispar de pe profil
  - L2: încearcă 5 login-uri greșite cu user A, apoi încearcă login corect cu user B (același IP) — userul B trebuie să poată loga
  - L3: cere reset password de 2 ori la rând, încearcă primul token — trebuie să fie invalid
  - L4: ca admin, schimbă rolul unui user logat în alt browser — userul trebuie redirectat la login data viitoare când navighează
  - L5: setează ceasul la 23:55 local și loghează — la 00:05 local, streak trebuie incrementat
  - L6: rezolvă o grilă, șterge-o din DB ca admin, mergi la admin → utilizatori și verifică că procentul nu e peste 100%
  - L7: greu de testat fără apache bench / curl paralel — verifică doar că `check_rate_limit` nu aruncă erori
