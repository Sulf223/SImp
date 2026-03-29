<section class="landing-shell" aria-label="Pagina de bun venit SImp">
    <header class="landing-header">
        <div class="logo-container">
            <span class="logo-landing">SImp</span>
            <span class="tagline">Inovatie in invatarea sortarii</span>
        </div>
        <div class="social-icons-landing" aria-label="Rețele sociale">
            <a href="#" class="icon fb" title="Facebook" aria-label="Facebook">fb</a>
            <a href="#" class="icon ig" title="Instagram" aria-label="Instagram">ig</a>
            <a href="#" class="icon yt" title="YouTube" aria-label="YouTube">yt</a>
        </div>
    </header>

    <main class="landing-main">
        <section class="innovation-section">
            <h1>C++: Nucleul Cunoasterii Sortarii</h1>

            <div class="solar-system-container" role="img" aria-label="Sistem solar educațional cu C++ în centru și sortări pe orbite">
                <div class="central-sun">C++</div>

                <div class="orbit orbit-python">
                    <a href="index.php?page=algoritmi_avansati" class="planet planet-python" data-stage="Limbaj secundar" title="Python">P</a>
                </div>

                <div class="orbit orbit-bubble">
                    <a href="index.php?page=sort_bubble" class="planet planet-bubble" data-stage="Prezentare" title="Bubble Sort">B</a>
                </div>

                <div class="orbit orbit-insertion">
                    <a href="index.php?page=sort_insertion" class="planet planet-insertion" data-stage="Consolidare" title="Insertion Sort">I</a>
                </div>

                <div class="orbit orbit-merge">
                    <a href="index.php?page=sort_merge" class="planet planet-merge" data-stage="Explicare" title="Merge Sort">M</a>
                </div>

                <div class="orbit orbit-selection">
                    <a href="index.php?page=sort_selection" class="planet planet-selection" data-stage="Exersare" title="Selection Sort">S</a>
                </div>

                <div class="orbit orbit-counting">
                    <a href="index.php?page=sort_counting" class="planet planet-counting" data-stage="Optimizare" title="Counting Sort">C</a>
                </div>

                <div class="orbit orbit-quick">
                    <a href="index.php?page=sort_quick" class="planet planet-quick" data-stage="Maestrie" title="Quick Sort">Q</a>
                </div>
            </div>

            <div class="solar-controls" aria-label="Control viteza animatie">
                <span>Viteza orbite:</span>
                <button type="button" data-speed="1.4">Lent</button>
                <button type="button" class="is-active" data-speed="1">Normal</button>
                <button type="button" data-speed="0.7">Rapid</button>
            </div>

            <div class="solar-legend">
                <span class="legend-item"><i class="legend-dot" style="background:#22a66d"></i>Bubble</span>
                <span class="legend-item"><i class="legend-dot" style="background:#0ea86b"></i>Insertion</span>
                <span class="legend-item"><i class="legend-dot" style="background:#0f6fdb"></i>Selection</span>
                <span class="legend-item"><i class="legend-dot" style="background:#11a057"></i>Quick</span>
                <span class="legend-item"><i class="legend-dot" style="background:#2f8eff"></i>Merge</span>
                <span class="legend-item"><i class="legend-dot" style="background:#1b7ae0"></i>Counting</span>
            </div>

            <div class="fundamental-links" aria-label="Toate lectiile fundamentale">
                <a class="fundamental-link" href="index.php?page=sort_bubble"><strong>Bubble Sort</strong><span>Comparatii adiacente</span></a>
                <a class="fundamental-link" href="index.php?page=sort_selection"><strong>Selection Sort</strong><span>Selecteaza minimul</span></a>
                <a class="fundamental-link" href="index.php?page=sort_insertion"><strong>Insertion Sort</strong><span>Inserare in secventa sortata</span></a>
                <a class="fundamental-link" href="index.php?page=sort_quick"><strong>Quick Sort</strong><span>Partiționare cu pivot</span></a>
                <a class="fundamental-link" href="index.php?page=sort_merge"><strong>Merge Sort</strong><span>Divide et impera</span></a>
                <a class="fundamental-link" href="index.php?page=sort_counting"><strong>Counting Sort</strong><span>Sortare prin frecvente</span></a>
            </div>

            <div class="solar-actions">
                <a href="index.php?page=login" class="btn-landing btn-start">Porniti Acum</a>
                <a href="index.php?page=algoritmi" class="link-more">Vezi Mai Multe</a>
            </div>
        </section>

        <section class="tech-section">
            <h2>Noi Folosim:</h2>
            <div class="tech-list">
                <span class="tech-item">C++</span>
                <span class="tech-item">Python</span>
                <span class="tech-item">CSS</span>
                <span class="tech-item">JS</span>
                <span class="tech-item">PHP</span>
                <span class="tech-item">MySQL</span>
            </div>
        </section>

        <section class="connect-section">
            <h2>Vino alaturi de noi!</h2>
            <div class="profile-container">
                <div class="profile-placeholder" aria-hidden="true">S</div>
                <div class="connect-actions">
                    <a href="index.php?page=register" class="btn-landing btn-signup">Inscriere</a>
                    <a href="index.php?page=login" class="btn-landing btn-login">Logare</a>
                </div>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <div>SImp © 2026</div>
        <div>Inovatie in invatarea sortarii</div>
    </footer>
</section>

<script>
(() => {
    const anchors = document.querySelectorAll('.landing-shell a[href]');
    anchors.forEach((a) => {
        const href = a.getAttribute('href');
        if (!href) return;
        if (href.includes('pagina=')) {
            a.setAttribute('href', href.replace('pagina=', 'page='));
        }
    });

    const buttons = document.querySelectorAll('.solar-controls button[data-speed]');
    buttons.forEach((btn) => {
        btn.addEventListener('click', () => {
            const speed = Number(btn.getAttribute('data-speed') || '1');
            document.documentElement.style.setProperty('--orbit-speed-factor', String(speed));
            buttons.forEach((b) => b.classList.remove('is-active'));
            btn.classList.add('is-active');
        });
    });
})();
</script>
