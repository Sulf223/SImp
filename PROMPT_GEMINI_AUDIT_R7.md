# Prompt Gemini CLI – Audit Round 7 (19 fix-uri OffByOne Academy)

Rulezi din `C:\wamp64\www\OffByOneAcademy`:

```powershell
gemini "$(Get-Content -Raw .\PROMPT_GEMINI_AUDIT_R7.md)"
```

⚠ Volum mare. Dacă răspunsul iese trunchiat, splitează prompt-ul pe grupuri (PHP / JS / CSS / CI).

---

## PROMPT (copiază tot)

Ești inginer software senior. Lucrezi în directorul curent. Aplicația OffByOne Academy e în `site_g/`. A trecut prin 6 runde de audit. Acum aplici 19 fix-uri descoperite în runda 7. La final scrii `AUDIT_R7_REPORT.md` cu tabel ID | fișier | linii | status.

### Reguli generale (NU le încălca)

- Folosește exclusiv tokeni CSS existenți (`var(--color-*)`, `var(--space-*)`, `var(--text-*)`).
- NU schimba semnătura funcțiilor PHP existente: `set_flash($type, $message)`, `verify_csrf()`, `csrf_field()`, `is_admin()`, `is_logged_in()`, `check_rate_limit()`, `log_admin_action()`, `check_and_award_achievements()`, `display_flash()`.
- Toate `<script>` inline → `nonce="<?= $nonce ?>"`.
- Toate query-urile → prepared statements.
- Comentariu pe fiecare modificare: `// FIX [A-ID]:` (PHP/JS) sau `/* FIX [A-ID]: */` (CSS).
- Validează `php -l` la final pe fișierele PHP modificate.

### Citește scurt rapoartele anterioare ca să nu strici fix-urile

Caută în rădăcină `AUDIT_FULL.md` (acest audit), apoi NU rescrie nimic ce e deja patch-uit conform rapoartelor anterioare ale rundelor 1-6.

---

## GRUPUL 1 — PHP BACKEND (10 fix-uri)

### [A2] HIGH — Session timeout pe AJAX endpoints

**Fișier-țintă:** `site_g/PHP/helpers.php` — adaugă funcție nouă.

```php
/**
 * Verifică și aplică timeout-ul de sesiune (30 min inactivitate).
 * Pentru endpoint-urile AJAX care trebuie să refuze cererea cu HTTP 401 dacă a expirat.
 * Apelează imediat după session_start().
 */
function enforce_session_timeout_ajax(int $max_inactivity_seconds = 1800): void {
    if (!isset($_SESSION['user_id'])) return; // anonim → nu enforce
    if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > $max_inactivity_seconds) {
        session_unset();
        session_destroy();
        http_response_code(401);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(['ok' => false, 'error' => 'Sesiune expirată', 'expired' => true]);
        exit;
    }
    $_SESSION['last_activity'] = time();
}
```

**Apelează în:** `ajax_progres.php`, `progres_api.php`, `ai_code_feedback.php`, `ai_quiz_api.php`, `profesor_ai_chat.php`, `admin_actions.php`, `admin_export.php` — imediat după `session_start()` și `require_once 'helpers.php'`.

### [A3] HIGH — `Cache-Control: no-store` pe endpoint-uri JSON

**Fișiere:** `ajax_progres.php`, `progres_api.php`, `ai_code_feedback.php`, `ai_quiz_api.php`, `profesor_ai_chat.php`.

Imediat după `header('Content-Type: application/json...')`, adaugă:
```php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
```

### [A7] MEDIUM — `last_activity_date = NULL` la admin reset_progress

**Fișier:** `site_g/PHP/admin_actions.php`, blocul `reset_progress`.

Verifică linia care face UPDATE pe `user_streak`. Trebuie să fie:
```sql
UPDATE user_streak SET current_streak = 0, longest_streak = 0, last_activity_date = NULL WHERE user_id = ?
```
Dacă în cod actual `last_activity_date = NULL` lipsește, adaugă-l. Dacă există deja, treci la următorul fix.

### [A8] MEDIUM — `getallheaders()` fallback pentru nginx

**Fișier:** `site_g/PHP/ai_code_feedback.php` linia 22-23 (și `helpers.php` în `verify_csrf_ajax` dacă există apel similar).

Înlocuiește:
```php
$headers = getallheaders();
$token = $headers['X-CSRF-Token'] ?? $headers['x-csrf-token'] ?? '';
```

Cu helper centralizat în `helpers.php`:
```php
function get_csrf_token_from_request(): string {
    $token = '';
    if (function_exists('getallheaders')) {
        $h = getallheaders();
        if (is_array($h)) {
            $token = $h['X-CSRF-Token'] ?? $h['x-csrf-token'] ?? '';
        }
    }
    if (!$token && isset($_SERVER['HTTP_X_CSRF_TOKEN'])) {
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'];
    }
    if (!$token && isset($_POST['csrf_token'])) {
        $token = $_POST['csrf_token'];
    }
    return is_string($token) ? $token : '';
}
```

În `ai_code_feedback.php`, înlocuiește blocul cu:
```php
$token = get_csrf_token_from_request();
if (!$token || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
    echo json_encode(['ok' => false, 'error' => 'Eroare CSRF.']); exit;
}
```

### [A9] MEDIUM — Validare tip flash

**Fișier:** `site_g/PHP/helpers.php`, funcția `set_flash`.

```php
function set_flash($type, $message) {
    if (!in_array($type, ['success', 'error', 'info'], true)) {
        error_log("set_flash: tip invalid '$type', folosit 'info'");
        $type = 'info';
    }
    $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
}
```

### [A10] MEDIUM — curl error logging în AI feedback

**Fișier:** `site_g/PHP/ai_code_feedback.php` blocul curl (~liniile 80–95).

Înlocuiește:
```php
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($http_code === 200 && $response) { ... }
```

Cu:
```php
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_err = curl_error($ch);
$curl_errno = curl_errno($ch);
curl_close($ch);

if ($response === false) {
    error_log("ai_code_feedback curl error #{$curl_errno}: {$curl_err}");
    echo json_encode(['ok' => false, 'error' => 'Serviciul AI este indisponibil. Încearcă mai târziu.']);
    exit;
}
if ($http_code !== 200) {
    error_log("ai_code_feedback HTTP {$http_code}: " . substr((string)$response, 0, 500));
    echo json_encode(['ok' => false, 'error' => 'AI a răspuns cu eroare (HTTP ' . $http_code . ').']);
    exit;
}
$json = json_decode($response, true);
if (!isset($json['choices'][0]['message']['content'])) {
    echo json_encode(['ok' => false, 'error' => 'Răspuns invalid de la AI.']);
    exit;
}
echo json_encode(['ok' => true, 'feedback' => trim($json['choices'][0]['message']['content'])]);
```

Aplică același pattern în `ai_quiz_api.php` și `profesor_ai_chat.php` dacă au structură identică.

### [A12] MEDIUM — Race condition achievements (verifică, posibil non-issue)

**Fișier:** `site_g/PHP/helpers.php`, funcția `check_and_award_achievements`.

`INSERT IGNORE` cu UNIQUE key (`uq_user_ach` pe `user_id, achievement_id`) e deja atomic — doar unul din 2 INSERT-uri concurente va avea `affected_rows > 0`. Dacă cei 2 admin/user-i ajung să facă query simultan, unul singur va vedea toast-ul. Asta e comportament corect.

**Acțiune Gemini:** verifică în `dbsortari.sql` și `upgrade_achievements.sql` că tabela `user_achievements` are `PRIMARY KEY (user_id, achievement_id)` sau `UNIQUE KEY uq_user_ach (user_id, achievement_id)`. Dacă DA, comentează în raport „A12 — non-issue, INSERT IGNORE atomic". Dacă NU, adaugă constraint într-o nouă migrație `database/upgrade_user_achievements_unique.sql`.

### [A13] LOW — Standardizare mysqli (procedural vs OOP)

**Acțiune:** NU schimba codul în această rundă (ar fi prea mare schimbarea). În raport, notează ca recomandare „pe termen lung, standardizați la OOP `$con->prepare()` peste tot".

---

## GRUPUL 2 — JAVASCRIPT (7 fix-uri)

### [A1] HIGH — Service Worker cu path relativ

**Fișier:** `site_g/JS/sw_register.js`.

Înlocuiește tot conținutul cu:
```javascript
// FIX [A1]: path relativ pentru a funcționa la orice deploy (root, subfolder, subdomeniu)
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('sw.js', { scope: './' })
            .catch(err => console.warn('SW register failed:', err));
    });
}
```

În `sw.js`, verifică că `ASSETS = [...]` folosește path-uri relative la rădăcina SW (e.g. `'index.php'`, nu `'/site_g/index.php'`). Dacă sunt absolute, rescrie cu relative.

### [A4] MEDIUM — Duplicate event listeners în `fundamental_visualizer.js`

**Fișier:** `site_g/JS/fundamental_visualizer.js`, funcția `refresh()` (~liniile 96–114).

Pattern-ul actual reatașează listeners la fiecare `refresh()`:
```javascript
function refresh() {
    btnPrev.addEventListener('click', () => { index--; refresh(); });
    btnNext.addEventListener('click', () => { index++; refresh(); });
    // ...
    render();
}
```

Refactorizează: atașează listeners O SINGURĂ DATĂ în `init()` / `DOMContentLoaded`, mută `index` într-o variabilă în closure, iar `refresh()` apelează doar `render()`:
```javascript
let currentIndex = 0;
btnPrev.addEventListener('click', () => { currentIndex = Math.max(0, currentIndex - 1); render(); });
btnNext.addEventListener('click', () => { currentIndex = Math.min(steps.length - 1, currentIndex + 1); render(); });
btnReset.addEventListener('click', () => { currentIndex = 0; render(); });

function render() {
    // folosește currentIndex pentru randare; NU mai atașează listeners
}

render(); // initial
```

### [A5] MEDIUM — try/catch pe fetch în AI feedback

**Fișier:** `site_g/JS/ai_code_feedback.js`, funcția care face `fetch('/PHP/ai_code_feedback.php', ...)`.

Înlocuiește pattern-ul:
```javascript
const res = await fetch(...);
const data = await res.json();
```

Cu:
```javascript
let data;
try {
    const res = await fetch('PHP/ai_code_feedback.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ code, context })
    });
    if (res.status === 401) {
        window.location.href = 'index.php?page=login&expired=1';
        return;
    }
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    data = await res.json();
} catch (e) {
    console.error('AI feedback error:', e);
    showError('Eroare la conectare. Verifică internetul și reîncearcă.');
    return;
} finally {
    button.disabled = false;
    spinner.style.display = 'none';
}
```

### [A6] MEDIUM — Web Audio context cleanup

**Fișier:** `site_g/JS/visualizer.js`, în `SortingVisualizer` clasa.

Adaugă o metodă `destroy()`:
```javascript
destroy() {
    if (this.audioContext && this.audioContext.state !== 'closed') {
        this.audioContext.close();
    }
    this.audioContext = null;
}
```

În constructor, după inițializare:
```javascript
window.addEventListener('beforeunload', () => this.destroy());
```

În metoda care toggle-ază sound (probabil `toggleSound()` sau ceva similar), când utilizatorul OPREȘTE sunetul:
```javascript
if (!this.soundEnabled && this.audioContext) {
    this.audioContext.close();
    this.audioContext = null;
}
```

### [A11] MEDIUM — Canvas timing fix

**Fișier:** `site_g/JS/visualizer.js` constructor (liniile 65–72).

Înlocuiește:
```javascript
this.resetArray();
if (this.canvas.width === 0 || (this.container && this.container.clientWidth === 0)) {
    requestAnimationFrame(() => this.onResize());
}
```

Cu:
```javascript
// FIX [A11]: trigger resize sincron + ResizeObserver pentru robustețe
this.onResize();
this.resetArray();
if (typeof ResizeObserver !== 'undefined' && this.container) {
    this._resizeObserver = new ResizeObserver(() => this.onResize());
    this._resizeObserver.observe(this.container);
}
```

În `destroy()` adaugat la A6, adaugă `if (this._resizeObserver) this._resizeObserver.disconnect();`.

### [A14] LOW — Paletă din CSS variables

**Fișier:** `site_g/JS/performance_compare.js` linia 165.

Înlocuiește:
```javascript
var palette = ["#2563eb", "#16a34a", "#f59e0b", "#ef4444", "#7c3aed", "#0ea5e9"];
```

Cu:
```javascript
function getPalette() {
    const get = v => getComputedStyle(document.documentElement).getPropertyValue(v).trim() || '#888';
    return [
        get('--color-primary'),
        get('--color-success'),
        get('--color-warning'),
        get('--color-danger'),
        get('--color-accent'),
        '#0ea5e9' // fallback dacă nu mai sunt tokeni
    ];
}
const palette = getPalette();
```

Dacă există un theme-toggle care schimbă `:root`, adaugă listener pe schimbarea de temă care reapelează `getPalette()` și redesenează chart-urile.

### [A16] LOW — `prefers-reduced-motion` pe sunete

**Fișier:** `site_g/JS/visualizer.js`, metoda `playTone()`.

La începutul metodei:
```javascript
playTone(value, kind) {
    if (!this.soundEnabled) return;
    // FIX [A16]: respectă preferința utilizatorului pentru reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    // ... restul codului
}
```

---

## GRUPUL 3 — CSS / HTML (3 fix-uri)

### [A15] LOW — Curățare CSS mort în `sortare.css`

**Fișier:** `site_g/CSS/sortare.css`.

Verifică în `site_g/pagini/sortare.php` dacă vreun card folosește `class="algorithm-card--bubble"`, `--quick`, etc. Dacă DA → lasă CSS-ul. Dacă NU → fie:

**Opțiunea A (recomandat):** adaugă clasele pe carduri în `sortare.php`:
```html
<article class="card algorithm-card algorithm-card--bubble">...</article>
```

**Opțiunea B:** șterge regulile mort din `sortare.css`.

Alege A pentru consistență cu sistem-ul de design.

### [A17] LOW — Verifică `font-display: swap` la Google Fonts

**Fișier:** `site_g/index.php`, în `<head>` linia ~108.

Verifică linkul `<link href="https://fonts.googleapis.com/css2?family=Inter...">`. Trebuie să conțină `&display=swap` la sfârșit. Dacă lipsește, adaugă-l:
```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
```

### [A18] LOW — Z-index conflict pe `bun_venit`

**Fișier:** `site_g/CSS/bun_venit.css` (caut `z-index` în jurul liniei 100).

Standardizează cu sistemul de tokeni:
- Decorativ (canvas solar system, mesh background) → `z-index: var(--z-base)` sau negativ
- UI activ (tooltip-uri, butoane, modal-uri) → `z-index: var(--z-tooltip)` sau peste

Verifică în `modern_vars.css` ce tokeni `--z-*` există și folosește-i în `bun_venit.css` în loc de numere magice.

---

## GRUPUL 4 — CI / TOOLING (1 fix)

### [A19] LOW — BOM check în GitHub Actions

**Fișier:** `.github/workflows/ci.yml`.

Adaugă un step nou înainte de „PHP lint":
```yaml
      - name: Verify no BOM in PHP files
        run: |
          BAD=$(find site_g -name "*.php" -exec sh -c 'head -c 3 "$1" | od -An -tx1 | grep -q "ef bb bf" && echo "$1"' _ {} \;)
          if [ -n "$BAD" ]; then
            echo "Files with UTF-8 BOM detected:"
            echo "$BAD"
            exit 1
          fi
```

Same logic pentru UTF-16 (`fe ff` sau `ff fe`).

---

## OUTPUT FINAL

`AUDIT_R7_REPORT.md` în rădăcină cu:
- Tabel: ID | Sev | Status (✅ aplicat / ⏳ skip cu motiv) | Fișier(e) | Linii
- Lista paginilor / endpoint-urilor testate manual:
  - `/site_g/index.php?page=admin&tab=actiuni` (acțiunile admin)
  - AJAX endpoints (cu DevTools verifică `Cache-Control: no-store`)
  - `/site_g/index.php?page=compilator` (AI feedback button)
  - `/site_g/manifest.json` (PWA install)
  - 404 page
  - Reset password flow
- Note despre A12 (race) și A13 (mysqli style) — non-issues
- Verificare `php -l` + (opțional) `vendor/bin/phpunit` dacă composer e instalat
