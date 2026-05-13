<style>
/* Stiluri specifice pentru pagina de bun venit (solar system) */
#solar-section { background: radial-gradient(ellipse at center, #0a0e27 0%, #000000 100%); }
.PLANETS { position: absolute; }
</style>

<div data-component="dashboard-modern">
    <header class="dash__header">
        <div class="dash__eyebrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/><path d="M5 3v4"/><path d="M19 17v4"/><path d="M3 5h4"/><path d="M17 19h4"/></svg>
            Inovație în învățare
        </div>
        <h2 class="dash__title">Bun venit la <span class="dash__title-accent">OffByOne Academy</span></h2>
        <p class="dash__lede">
            Explorează universul algoritmilor de sortare prin vizualizări interactive și explicații pas cu pas.
            OffByOne Academy transformă învățarea într-o experiență captivantă și educativă.
        </p>
    </header>

    <main class="bento" style="gap: var(--space-6);">
        <!-- HERO: Solar System Canvas -->
        <div class="card bento__card--hero" style="min-height: 600px; padding: 0; overflow: hidden; background: #080e1f; border: none; border-radius: var(--radius-lg);">
            <section id="solar-section" aria-label="Sistem solar interactiv metode de sortare" style="width: 100%; height: 100%; margin: 0; border-radius: 0;">
                <canvas id="stars-canvas" style="position: absolute; inset: 0;"></canvas>
                <div id="hero-title" style="position: absolute; top: 32px; left: 50%; transform: translateX(-50%); text-align: center; z-index: 2; pointer-events: none;">
                    <h1 style="font-size: clamp(18px, 3vw, 26px); font-weight: 300; color: rgba(255, 255, 255, 0.5); letter-spacing: 4px; text-transform: uppercase; margin: 0;">Metode de Sortare</h1>
                </div>
                <canvas id="solar-canvas" style="position: relative; z-index: 1; display: block; width: 100%; height: 100%;"></canvas>
                <div id="click-hint" style="position: absolute; bottom: 60px; left: 50%; transform: translateX(-50%); text-align: center; z-index: 2; pointer-events: none; color: rgba(255, 255, 255, 0.2); font-size: 12px;">Hover pentru detalii - Click pentru a intra în lecție</div>
                <div id="hero-subtitle" style="position: absolute; bottom: 32px; left: 50%; transform: translateX(-50%); text-align: center; z-index: 2; pointer-events: none; color: rgba(255, 255, 255, 0.3); font-size: 13px; letter-spacing: 2px;">OffByOne Academy - Inovație în învățarea sortării</div>
                <div id="tooltip" style="position: fixed; z-index: var(--z-tooltip); background: rgba(8, 14, 31, 0.95); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 12px; padding: 14px 18px; pointer-events: none; opacity: 0; transition: opacity 0.2s ease; max-width: 220px; backdrop-filter: blur(8px);">
                    <h3 id="tt-name" style="font-size: 15px; font-weight: 600; margin-bottom: 6px; color: #fff;"></h3>
                    <p id="tt-desc" style="font-size: 13px; line-height: 1.5; color: rgba(255, 255, 255, 0.7); margin: 0;"></p>
                    <div class="complexity" id="tt-complex" style="margin-top: 8px; font-size: 11px; color: rgba(255, 255, 255, 0.45); font-family: monospace; letter-spacing: 0.5px;"></div>
                </div>
            </section>
        </div>

        <!-- CTA: Sign Up -->
        <div class="card card--ai bento__card--ai" style="border: 1px solid var(--color-primary-soft); background: linear-gradient(135deg, rgba(110, 86, 207, 0.05) 0%, rgba(6, 182, 212, 0.03) 100%); position: relative; overflow: hidden;">
            <div style="position: absolute; top: -50%; right: -50%; width: 300px; height: 300px; background: radial-gradient(circle, var(--color-primary-glow) 0%, transparent 70%); opacity: 0.1; z-index: 0;"></div>
            <div class="ai__icon-wrap" style="position: relative; z-index: 1;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
            <h3 class="card__title-sm" style="position: relative; z-index: 1;">Alătură-te comunității!</h3>
            <p class="card__body" style="position: relative; z-index: 1; color: var(--color-fg-muted);">Creează un cont pentru a-ți urmări progresul, accesa Profesorul AI și scala-ți abilitățile.</p>
            <div class="card__actions" style="position: relative; z-index: 1;">
                <a href="index.php?page=register" class="btn btn--primary">Înscrie-te acum</a>
                <a href="index.php?page=login" class="btn btn--ghost">Ai deja cont?</a>
            </div>
        </div>

        <!-- STATS: 2-column grid -->
        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-success-soft); background: linear-gradient(135deg, rgba(34, 197, 94, 0.05) 0%, transparent 100%);">
            <span class="stat__label" style="color: var(--color-success); display: inline-flex; align-items: center; gap: 6px;">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                </svg>
                Lecții Active
            </span>
            <div class="stat__value" style="color: var(--color-success);">12+</div>
            <p class="stat__sub">Algoritmi fundamentali și avansați, demonstrații live.</p>
        </div>

        <div class="card card--stat bento__card--stat" style="border: 1px solid var(--color-accent-soft); background: linear-gradient(135deg, rgba(6, 182, 212, 0.05) 0%, transparent 100%);">
            <span class="stat__label" style="color: var(--color-accent); display: inline-flex; align-items: center; gap: 6px;">
                <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                </svg>
                Tehnologii
            </span>
            <div class="stat__value" style="color: var(--color-accent); font-size: var(--text-xl);">Modern</div>
            <p class="stat__sub">C++17, Python, JavaScript, PHP, MySQL, Canvas APIs.</p>
        </div>

        <!-- QUICK LINKS: Full-width card -->
        <div class="card bento__card--timeline" style="grid-column: 1 / -1; border: 1px solid var(--color-border); background: var(--color-surface-1);">
            <div class="card__head">
                <h3 class="card__title" style="display: flex; align-items: center; gap: var(--space-2);">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                    Acces Rapid
                </h3>
            </div>
            <div class="card__body">
                <p style="font-size: var(--text-sm); color: var(--color-fg-muted); margin-bottom: var(--space-4);">Navighez direct la lecțiile tale preferate:</p>
                <div class="fundamental-links" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: var(--space-3); margin-top: var(--space-3);">
                    <a class="btn btn--ghost btn--sm" href="index.php?page=algoritmi_fundamentali" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                        Algoritmi Fundamentali
                    </a>
                    <a class="btn btn--ghost btn--sm" href="index.php?page=sort_bubble" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="2"/><circle cx="18" cy="6" r="2"/><circle cx="6" cy="18" r="2"/><circle cx="18" cy="18" r="2"/></svg>
                        Bubble Sort
                    </a>
                    <a class="btn btn--ghost btn--sm" href="index.php?page=sort_selection" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Selection Sort
                    </a>
                    <a class="btn btn--ghost btn--sm" href="index.php?page=sort_insertion" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        Insertion Sort
                    </a>
                    <a class="btn btn--ghost btn--sm" href="index.php?page=sort_quick" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
                        Quick Sort
                    </a>
                    <a class="btn btn--ghost btn--sm" href="index.php?page=sort_merge" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 3 21 3 21 8"/><line x1="4" y1="20" x2="21" y2="3"/><polyline points="21 16 21 21 16 21"/><line x1="15" y1="15" x2="21" y2="21"/><line x1="4" y1="4" x2="9" y2="9"/></svg>
                        Merge Sort
                    </a>
                    <a class="btn btn--ghost btn--sm" href="index.php?page=sort_counting" style="justify-content: flex-start;">
                        <svg class="icon icon--xs" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="20" x2="12" y2="10"/><line x1="18" y1="20" x2="18" y2="4"/><line x1="6" y1="20" x2="6" y2="16"/></svg>
                        Counting Sort
                    </a>
                </div>
            </div>
        </div>
    </main>
</div>

<style>
/* Fix: Anulează pointer-events din stil.css pentru a face butoanele clickabile */
#solar-section * {
    pointer-events: auto !important;
}
</style>

<script nonce="<?= $nonce ?>">
// FIX [M2]: Adăugare nonce pentru CSP
(() => {
    const PLANETS = [
        {
            name: 'Bubble Sort',
            desc: 'Comparatii adiacente + interschimbari repetate',
            complexity: 'O(n^2) timp · O(1) spatiu',
            color: '#ff6b6b',
            glow: 'rgba(255,107,107,0.4)',
            radius: 18,
            orbitA: 200,
            orbitB: 75,
            orbitTilt: -18,
            speed: 0.45,
            phase: 0,
            href: 'index.php?page=sort_bubble'
        },
        {
            name: 'Selection Sort',
            desc: 'Selecteaza minimul si il aduce pe pozitia curenta',
            complexity: 'O(n^2) timp · O(1) spatiu',
            color: '#3b82f6',
            glow: 'rgba(59,130,246,0.4)',
            radius: 16,
            orbitA: 260,
            orbitB: 95,
            orbitTilt: 12,
            speed: 0.33,
            phase: 1.05,
            href: 'index.php?page=sort_selection'
        },
        {
            name: 'Insertion Sort',
            desc: 'Construieste secventa sortata prin inserare',
            complexity: 'O(n^2) timp · O(1) spatiu',
            color: '#22c55e',
            glow: 'rgba(34,197,94,0.4)',
            radius: 15,
            orbitA: 165,
            orbitB: 60,
            orbitTilt: 30,
            speed: 0.58,
            phase: 2.1,
            href: 'index.php?page=sort_insertion'
        },
        {
            name: 'Quick Sort',
            desc: 'Divide et Impera bazat pe pivot si partitionare',
            complexity: 'O(n log n) mediu · O(n^2) worst',
            color: '#a855f7',
            glow: 'rgba(168,85,247,0.4)',
            radius: 22,
            orbitA: 310,
            orbitB: 110,
            orbitTilt: -8,
            speed: 0.26,
            phase: 3.67,
            href: 'index.php?page=sort_quick'
        },
        {
            name: 'Merge Sort',
            desc: 'Imparte vectorul si interclaseaza recursiv',
            complexity: 'O(n log n) timp · O(n) spatiu',
            color: '#facc15',
            glow: 'rgba(250,204,21,0.4)',
            radius: 20,
            orbitA: 240,
            orbitB: 88,
            orbitTilt: -28,
            speed: 0.38,
            phase: 4.71,
            href: 'index.php?page=sort_merge'
        },
        {
            name: 'Counting Sort',
            desc: 'Numarare frecvente, eficient pentru valori in interval mic',
            complexity: 'O(n + k) timp · O(k) spatiu',
            color: '#48cae4',
            glow: 'rgba(72,202,228,0.4)',
            radius: 14,
            orbitA: 290,
            orbitB: 100,
            orbitTilt: 22,
            speed: 0.29,
            phase: 5.76,
            href: 'index.php?page=sort_counting'
        }
    ];

    const section = document.getElementById('solar-section');
    const starsCanvas = document.getElementById('stars-canvas');
    const canvas = document.getElementById('solar-canvas');
    const tooltip = document.getElementById('tooltip');
    const ttName = document.getElementById('tt-name');
    const ttDesc = document.getElementById('tt-desc');
    const ttComplex = document.getElementById('tt-complex');

    if (!section || !starsCanvas || !canvas || !tooltip || !ttName || !ttDesc || !ttComplex) {
        return;
    }

    const starsCtx = starsCanvas.getContext('2d');
    const ctx = canvas.getContext('2d');
    let W = 0;
    let H = 0;
    let cx = 0;
    let cy = 0;
    let stars = [];
    let hoveredPlanet = null;
    let time = 0;

    function resize() {
        W = section.clientWidth;
        H = section.clientHeight;
        starsCanvas.width = W;
        starsCanvas.height = H;
        canvas.width = W;
        canvas.height = H;
        cx = W / 2;
        cy = H / 2;
        generateStars();
        drawStars();
    }

    function generateStars() {
        stars = [];
        const count = Math.floor((W * H) / 4000);
        for (let i = 0; i < count; i++) {
            stars.push({
                x: Math.random() * W,
                y: Math.random() * H,
                r: Math.random() * 1.5 + 0.3,
                alpha: Math.random() * 0.7 + 0.2,
                twinkle: Math.random() * Math.PI * 2
            });
        }
    }

    function drawStars() {
        starsCtx.clearRect(0, 0, W, H);
        stars.forEach((s) => {
            const a = s.alpha + Math.sin(time * 0.5 + s.twinkle) * 0.15;
            starsCtx.beginPath();
            starsCtx.arc(s.x, s.y, s.r, 0, Math.PI * 2);
            starsCtx.fillStyle = `rgba(255,255,255,${Math.max(0, Math.min(1, a))})`;
            starsCtx.fill();
        });
    }

    function getPlanetPos(p, t) {
        const angle = t * p.speed + p.phase;
        const tilt = p.orbitTilt * Math.PI / 180;
        const ex = p.orbitA * Math.cos(angle);
        const ey = p.orbitB * Math.sin(angle);
        const x = cx + ex * Math.cos(tilt) - ey * Math.sin(tilt);
        const y = cy + ex * Math.sin(tilt) + ey * Math.cos(tilt);
        const depth = Math.sin(angle + tilt);
        return { x, y, depth };
    }

    function drawOrbit(p) {
        const tilt = p.orbitTilt * Math.PI / 180;
        ctx.save();
        ctx.translate(cx, cy);
        ctx.rotate(tilt);
        ctx.scale(1, p.orbitB / p.orbitA);
        ctx.beginPath();
        ctx.arc(0, 0, p.orbitA, 0, Math.PI * 2);
        ctx.restore();
        ctx.strokeStyle = 'rgba(255,255,255,0.06)';
        ctx.lineWidth = 1;
        ctx.stroke();
    }

    function drawSun() {
        const r = 54;
        ctx.save();
        const corona = ctx.createRadialGradient(cx, cy, r * 0.6, cx, cy, r * 2.2);
        corona.addColorStop(0, 'rgba(255,180,60,0.18)');
        corona.addColorStop(0.5, 'rgba(255,120,30,0.07)');
        corona.addColorStop(1, 'rgba(255,80,0,0)');
        ctx.beginPath();
        ctx.arc(cx, cy, r * 2.2, 0, Math.PI * 2);
        ctx.fillStyle = corona;
        ctx.fill();
        ctx.restore();

        const sunGrad = ctx.createRadialGradient(cx - r * 0.3, cy - r * 0.3, r * 0.1, cx, cy, r);
        sunGrad.addColorStop(0, '#ffe566');
        sunGrad.addColorStop(0.45, '#ffad1f');
        sunGrad.addColorStop(1, '#e05c00');
        ctx.beginPath();
        ctx.arc(cx, cy, r, 0, Math.PI * 2);
        ctx.fillStyle = sunGrad;
        ctx.fill();

        ctx.fillStyle = 'rgba(255,255,255,0.92)';
        ctx.font = 'bold 15px Segoe UI, system-ui, sans-serif';
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText('Sortare', cx, cy);
    }

    function lighten(hex, amt) {
        const r = Math.min(255, parseInt(hex.slice(1, 3), 16) + amt);
        const g = Math.min(255, parseInt(hex.slice(3, 5), 16) + amt);
        const b = Math.min(255, parseInt(hex.slice(5, 7), 16) + amt);
        return `rgb(${r},${g},${b})`;
    }

    function darken(hex, amt) {
        const r = Math.max(0, parseInt(hex.slice(1, 3), 16) - amt);
        const g = Math.max(0, parseInt(hex.slice(3, 5), 16) - amt);
        const b = Math.max(0, parseInt(hex.slice(5, 7), 16) - amt);
        return `rgb(${r},${g},${b})`;
    }

    function drawPlanet(p, pos, isHovered) {
        const scale = isHovered ? 1.4 : (0.82 + (pos.depth + 1) * 0.09);
        const r = p.radius * scale;

        if (isHovered) {
            ctx.save();
            const hg = ctx.createRadialGradient(pos.x, pos.y, r, pos.x, pos.y, r * 2.8);
            hg.addColorStop(0, p.glow.replace('0.4', '0.5'));
            hg.addColorStop(1, 'rgba(0,0,0,0)');
            ctx.beginPath();
            ctx.arc(pos.x, pos.y, r * 2.8, 0, Math.PI * 2);
            ctx.fillStyle = hg;
            ctx.fill();
            ctx.restore();
        }

        const grad = ctx.createRadialGradient(pos.x - r * 0.35, pos.y - r * 0.35, r * 0.05, pos.x, pos.y, r);
        grad.addColorStop(0, lighten(p.color, 60));
        grad.addColorStop(0.5, p.color);
        grad.addColorStop(1, darken(p.color, 50));

        ctx.beginPath();
        ctx.arc(pos.x, pos.y, r, 0, Math.PI * 2);
        ctx.fillStyle = grad;
        ctx.fill();

        if (isHovered) {
            ctx.strokeStyle = p.color;
            ctx.lineWidth = 2;
            ctx.stroke();
        }

        ctx.fillStyle = '#fff';
        ctx.font = `bold ${Math.max(8, 10 * scale)}px Segoe UI, system-ui, sans-serif`;
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        const short = p.name.split(' ')[0];
        ctx.fillText(short, pos.x, pos.y - 1);
    }

    function draw() {
        ctx.clearRect(0, 0, W, H);
        PLANETS.forEach((p) => drawOrbit(p));
        const positions = PLANETS.map((p) => ({ planet: p, pos: getPlanetPos(p, time) }));
        positions.sort((a, b) => a.pos.depth - b.pos.depth);

        positions.forEach(({ planet, pos }) => {
            if (planet !== hoveredPlanet) {
                drawPlanet(planet, pos, false);
            }
        });

        drawSun();

        if (hoveredPlanet) {
            const pos = getPlanetPos(hoveredPlanet, time);
            drawPlanet(hoveredPlanet, pos, true);
        }
        drawStars();
    }

    function animate() {
        time += 0.008;
        draw();
        requestAnimationFrame(animate);
    }

    canvas.addEventListener('mousemove', (e) => {
        const rect = canvas.getBoundingClientRect();
        const mx = e.clientX - rect.left;
        const my = e.clientY - rect.top;
        let found = null;
        let minDist = Infinity;

        PLANETS.forEach((p) => {
            const pos = getPlanetPos(p, time);
            const dist = Math.hypot(mx - pos.x, my - pos.y);
            const hitR = p.radius * 1.5 + 8;
            if (dist < hitR && dist < minDist) {
                minDist = dist;
                found = p;
            }
        });

        hoveredPlanet = found;
        canvas.style.cursor = found ? 'pointer' : 'default';

        if (found) {
            ttName.textContent = found.name;
            ttDesc.textContent = found.desc;
            ttComplex.textContent = found.complexity;
            ttName.style.color = found.color;
            const tx = Math.min(e.clientX + 16, window.innerWidth - 240);
            const ty = Math.min(e.clientY - 10, window.innerHeight - 140);
            tooltip.style.left = tx + 'px';
            tooltip.style.top = ty + 'px';
            tooltip.classList.add('visible');
        } else {
            tooltip.classList.remove('visible');
        }
    });

    canvas.addEventListener('mouseleave', () => {
        hoveredPlanet = null;
        tooltip.classList.remove('visible');
    });

    canvas.addEventListener('click', () => {
        if (hoveredPlanet && hoveredPlanet.href) {
            window.location.href = hoveredPlanet.href;
        }
    });

    window.addEventListener('resize', resize);
    resize();
    animate();
})();
</script>
