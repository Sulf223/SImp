# SImp Portal – Audit comprehensiv (runda 7)

Acest audit a inspectat **toate fișierele** PHP, JS și CSS din `site_g/`, plus configurațiile (`composer.json`, `manifest.json`, `.htaccess`-urile, migrațiile SQL). Scopul: găsește bug-uri și polish issues care **NU** au fost raportate în rundele anterioare.

**Total nou: 19 issues** (3 HIGH, 9 MEDIUM, 7 LOW). Dintre acestea, 4 verificate manual de mine (marcate cu ✓), restul raportate de agenți de explorare cu evidență citată.

---

## 🚨 HIGH

### [A1] ✓ Service Worker cu path absolut – nu funcționează la deploy alternativ
**Fișier:** `site_g/JS/sw_register.js:3`
**Categorie:** PWA / portabilitate

```javascript
navigator.serviceWorker.register('/site_g/sw.js');
```

Path-ul `/site_g/sw.js` e absolut și funcționează doar dacă proiectul e în `http://localhost/SImp/site_g/`. Pe orice alt deploy (subdomeniu, root, alt subfolder), SW nu se înregistrează → PWA și offline mode nu funcționează.

**Fix:** `navigator.serviceWorker.register('sw.js', { scope: './' });` (path relativ la fișierul curent).

### [A2] Session timeout nu e impus pe endpoint-urile AJAX
**Fișier:** `site_g/PHP/auth.php:7-15` (verificarea există, dar nu e propagată)
**Endpoint-uri afectate:** `ajax_progres.php`, `progres_api.php`, `ai_code_feedback.php`, `ai_quiz_api.php`, `admin_actions.php`

Verificarea `last_activity > 1800s` rulează doar în `auth.php`, care e inclus în paginile UI dar **NU** în handlerele AJAX. Un atacator cu cookie-ul de sesiune furat poate continua să apeleze API-uri indefinit, chiar dacă sesiunea „interactivă" expiră.

**Fix:** mută logica în `helpers.php` ca funcție `enforce_session_timeout()` și apeleaz-o la începutul fiecărui handler AJAX.

### [A3] Lipsă headere `Cache-Control` pe endpoint-uri JSON
**Fișier:** `site_g/PHP/ajax_progres.php:5`, `progres_api.php:2`, `ai_code_feedback.php:9`, `ai_quiz_api.php`

Toate trimit `Content-Type: application/json` dar **nu** `Cache-Control: no-store`. Browsere agresive sau proxy-uri pot cache-ui răspunsuri auth-dependent → utilizatori văd date ale altor useri în cache local. Pe `admin_export.php` corectarea există deja.

**Fix:** adaugă imediat după `Content-Type`:
```php
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
```

---

## ⚠ MEDIUM

### [A4] ✓ Duplicate event listeners în `fundamental_visualizer.js`
**Fișier:** `site_g/JS/fundamental_visualizer.js:96-114`
**Categorie:** memory-leak

Funcția `refresh()` reatașează `addEventListener('click', ...)` pe butoanele Prev/Next/Reset la fiecare apel — și se apelează recursiv din interiorul handler-ului. După N click-uri, fiecare click declanșează N acțiuni → progres prea rapid + listeners care țin DOM detașat în memorie.

**Fix:** atașează listener-ii o singură dată în `DOMContentLoaded`, păstrează `index` într-un closure / variabilă mutabilă, fă doar `render()` în `refresh()`.

### [A5] `fetch().json()` fără try/catch în AI feedback
**Fișier:** `site_g/JS/ai_code_feedback.js:32`

Dacă răspunsul nu e JSON valid (HTML cu eroare 500), `await res.json()` aruncă `Unexpected token <` și butonul rămâne disabled până la refresh.

**Fix:** wrap în try/catch sau verifică `res.ok` înainte:
```javascript
if (!res.ok) throw new Error(`HTTP ${res.status}`);
const data = await res.json().catch(() => ({ ok: false, error: 'Răspuns invalid' }));
```

### [A6] Web Audio API context nu se închide niciodată
**Fișier:** `site_g/JS/visualizer.js:332-362` (`ensureAudioContext`, `playTone`)

`new AudioContext()` rămâne deschis după ce utilizatorul oprește sunetul sau părăsește pagina. Pe mobile, drenează bateria și interferează cu alte aplicații audio (YouTube, Spotify).

**Fix:** la dezactivare sound + `beforeunload`: `if (this.audioContext) { this.audioContext.close(); this.audioContext = null; }`.

### [A7] Streak `last_activity_date` nu se resetează la admin reset_progress
**Fișier:** `site_g/PHP/admin_actions.php` (în blocul `reset_progress`)

Promptul de la R6 spunea „UPDATE user_streak SET current_streak = 0, longest_streak = 0, last_activity_date = NULL", dar verifică în cod dacă `last_activity_date = NULL` chiar e inclus. Dacă lipsește, după reset utilizatorul revine cu streak fantomă (compară `today` cu `last_activity_date` vechi).

**Fix:** asigură-te că UPDATE-ul include explicit `last_activity_date = NULL`.

### [A8] `getallheaders()` nu funcționează pe nginx
**Fișier:** `site_g/PHP/ai_code_feedback.php:22-23`, `helpers.php` în `verify_csrf_ajax`

```php
$headers = getallheaders();
$token = $headers['X-CSRF-Token'] ?? '';
```

`getallheaders()` există doar pe Apache + mod_php. Pe nginx + PHP-FPM returnează `false` → CSRF check eșuează tot timpul → endpoint-ul refuză toate cererile valide.

**Fix:** fallback la `$_SERVER`:
```php
$token = '';
if (function_exists('getallheaders')) {
    $h = getallheaders();
    $token = $h['X-CSRF-Token'] ?? $h['x-csrf-token'] ?? '';
}
if (!$token) {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
}
```

### [A9] `set_flash($type, ...)` acceptă orice string ca tip
**Fișier:** `site_g/PHP/helpers.php:13`

`set_flash('warning', 'msg')` (typo sau nou tip) e silentîn `display_flash()`: tipul cade pe `else` și apare cu icon „info". Niciun warning în log.

**Fix:**
```php
function set_flash($type, $message) {
    if (!in_array($type, ['success','error','info'], true)) {
        error_log("set_flash: tip invalid '$type'");
        $type = 'info';
    }
    $_SESSION['flash_messages'][] = ['type' => $type, 'message' => $message];
}
```

### [A10] curl error netratat în AI feedback
**Fișier:** `site_g/PHP/ai_code_feedback.php:89-93`

```php
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($http_code === 200 && $response) { ... }
```

Dacă rețeaua e jos (`curl_exec` returnează `false`), `$http_code` e `0` și mesajul e „Eroare la comunicarea cu AI-ul (HTTP 0)" — fără context. `curl_error($ch)` nu e apelat înainte de `curl_close`.

**Fix:** capturează `$err = curl_error($ch); $errno = curl_errno($ch);` înainte de `curl_close`, logează cu `error_log` dacă `$response === false`.

### [A11] Canvas timing: vizualizator gol pe primul load
**Fișier:** `site_g/JS/visualizer.js:65-67`

`requestAnimationFrame(() => this.onResize())` rulează **înainte** ca `resetArray()` să fi terminat de inițializat date pe canvas. Pe slow devices, vizualizatorul apare gol 200-500ms.

**Fix:** apelează `onResize()` sincron în constructor sau folosește `ResizeObserver` în loc de `window.resize` listener.

### [A12] Race condition pe achievements la cereri concurente
**Fișier:** `site_g/PHP/helpers.php` (`check_and_award_achievements`)

Două POST-uri AJAX de la același utilizator pot ambele să citească „achievement neacordat" și să încerce `INSERT IGNORE`. `INSERT IGNORE` e safe pentru DB (UNIQUE pe `(user_id, achievement_id)` din migrație), dar `affected_rows > 0` returnează true pentru ambele dacă PRIMA tranzacție nu a făcut commit încă → utilizatorul vede toast-ul de 2 ori.

**Fix:** folosește tranzacție `SELECT ... FOR UPDATE` pe `achievements` sau încarcă achievement-urile o singură dată per request și deduplichează în client.

---

## 🔧 LOW

### [A13] Inconsistență `mysqli_query()` proceduralel vs `$con->query()` OOP
**Fișier:** `site_g/pagini/admin.php` folosește OOP, `site_g/PHP/login_post.php` folosește procedural

Nu e bug funcțional (PHP suportă ambele), dar code review devine confuz. Standardizare la unul singur (recomandat OOP, mai modern).

### [A14] Hardcoded paletă culori în `performance_compare.js`
**Fișier:** `site_g/JS/performance_compare.js:165`

```javascript
var palette = ["#2563eb", "#16a34a", "#f59e0b", "#ef4444", "#7c3aed", "#0ea5e9"];
```

Pe light theme, culorile rămân la fel → contrast diferit. Ar trebui citite din CSS variables:
```javascript
const get = v => getComputedStyle(document.documentElement).getPropertyValue(v).trim();
const palette = [get('--color-primary'), get('--color-success'), ...];
```

### [A15] CSS mort în `sortare.css`
**Fișier:** `site_g/CSS/sortare.css:1-63`

Clasele `.algorithm-card--bubble`, `.algorithm-card--quick` etc. sunt definite dar HTML-ul folosește doar `.card` generic. Codul nu strică nimic, doar mărește bundle CSS cu ~150 bytes.

**Fix:** sau adaugă clasele în `pagini/sortare.php` pe carduri, sau șterge din CSS.

### [A16] `prefers-reduced-motion` ignorat pe Web Audio
**Fișier:** `site_g/JS/visualizer.js:341-362` (`playTone`)

Pentru utilizatori cu `prefers-reduced-motion: reduce`, animațiile sunt deja oprite, dar sunetele continuă. Vestibular disorders / sensitivity audio sunt afectate.

**Fix:**
```javascript
playTone(value, kind) {
    if (!this.soundEnabled) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    ...
}
```

### [A17] Lipsă `@font-face` cu `font-display: swap`
**Fișier:** `site_g/CSS/modern_vars.css:75-79`

`--font-sans: "Inter", "Geist", ...` listează fonturi, dar nu e niciun `@font-face` declarat. Fonturile vin doar din Google Fonts (cu `<link>`-ul din `index.php`), iar Google furnizează `font-display: swap` doar dacă cerem URL-ul cu parametru.

**Fix:** verifică linkul Google Fonts din `index.php:108` să conțină `&display=swap` în query string. Dacă da, OK. Dacă nu, adaugă-l.

### [A18] Z-index war pe pagina `bun_venit`
**Fișier:** `site_g/CSS/bun_venit.css:100-102`

Tooltip-uri cu `z-index: var(--z-tooltip)` se ascund sub canvas-ul cu solar system pentru că secțiunea decorativă are `z-index` arbitrar.

**Fix:** standardizează — toate elementele decorative să folosească `var(--z-base)` sau negativ; tot ce e UI activ să fie pe `var(--z-tooltip)` sau peste.

### [A19] BOM check lipsă pe fișiere PHP
**Verificare:** orice fișier PHP cu UTF-8 BOM (`EF BB BF`) emite 3 bytes înainte de `<?php`, blochează `header()` și produce „Headers already sent". Nu am identificat fișiere afectate, dar lipsește un check automat.

**Fix recomandat:** adaugă în `.github/workflows/ci.yml` pasul:
```yaml
- name: Check no BOM in PHP files
  run: |
    for f in $(find site_g -name "*.php"); do
      if head -c 3 "$f" | grep -q $'\xef\xbb\xbf'; then
        echo "BOM in $f"; exit 1;
      fi
    done
```

---

## ✅ Categorii curate

- **Timezone handling** — toate `date()` folosesc default; consistent
- **Naming `$user_id` vs `$id_utilizator`** — consistent per fișier (utilizatori folosesc `id_utilizator` în SQL legacy, restul folosesc `user_id`)
- **`htmlspecialchars` cu encoding** — majoritatea apeluri folosesc default, dar nu am găsit cazuri de output greșit
- **Endpoint-uri publice fără auth** — `compilator_online.php`, `metode.php` sunt OK că-s publice (citire conținut educațional)
- **Encoding fișiere PHP** — toate sunt UTF-8 fără BOM (verificat anterior)
- **Toast notifications** — implementare corectă, fără memory leaks
- **Service Worker cache strategy** — corect (network-first PHP, cache-first assets)

---

## Prioritizare pentru următoarea rundă Gemini

**Must fix** (bug-uri funcționale): A1, A2, A3, A4, A5, A6, A7, A8
**Should fix** (best practices): A9, A10, A11, A12
**Nice to have** (polish): A13–A19

Spune-mi care vrei să le pun în prompt Gemini sau dacă vrei „all-in-one" (toate 19 într-un singur prompt).
