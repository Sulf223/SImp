# FIX REPORT R2 - SImp Portal

Rezumatul reparării bug-urilor din runda a doua (Round 2).

| ID | Fișier | Linii | Justificare |
|:---|:---|:---|:---|
| **[C1]** | `site_g/pagini/invatare.php` | 11-30 | Optimizare interogări (eliminare N+1) și prevenire potențial SQL Injection prin preluare bulk a datelor. |
| **[M7]** | `site_g/PHP/grila_interactiva.php` | Multiplu | Adăugare radix `10` la apelurile `parseInt()` pentru consistență și securitate. (Verificat și în `JS/visualizer.js`, deja prezent). |
| **[M8]** | `site_g/JS/ai_widget.js` | 266-268 | Salvare interval într-o variabilă și curățare pe evenimentul `beforeunload` pentru a preveni memory leaks. |
| **[M9]** | `site_g/PHP/register.php`, `site_g/PHP/register_post.php` | 33, 23-28 | Adăugare `maxlength="64"` în frontend și validare lungime (3-64) în backend pentru username. |

## Note suplimentare
- Panoul admin (`admin.php`, `admin_actions.php`, `admin_export.php`) a fost verificat și a rămas nemodificat, conform cerințelor.
- Toate modificările au fost marcate cu comentarii de tip `// FIX [ID]:`.
