# OffByOne Academy - Project Instructions

## Architecture & Conventions
- **Routing:** Centralized in `index.php` with an allowed-list of pages.
- **Styling:** Vanilla CSS using Design Tokens in `CSS/modern_vars.css`. Prefer CSS variables for colors, spacing, and typography.
- **Icons:** Use Lucide-style SVG icons only. No emojis or font icons.
- **Javascript:** Modular JS in `JS/` folder. Use `SortingVisualizer` class for animations.
- **Security:**
    - Never expose `.env` to the web root.
    - Always use `csrf_field()` in forms and `validate_csrf()` in POST handlers.
    - Use secure session cookies.
    - All database queries should use prepared statements (if user input is involved).

## Development Workflow
- **Docker:** Use `docker compose up -d` to start the environment. DB migrations are automatic via `docker-entrypoint-initdb.d`.
- **Adding a Page:**
    1. Create the file in `site_g/pagini/`.
    2. Add the entry to `$pagini_permise` in `index.php`.
- **Styling Changes:** Always check `CSS/modern_vars.css` first to ensure consistency with the design system.

## Pedagogical Standards
- Every algorithm page must include:
    - Interactive visualizer.
    - Pseudo-code block with `data-line` attributes for highlighting.
    - Variable inspector (`data-var-inspector`).
    - Efficiency stats (Time/Space complexity).
