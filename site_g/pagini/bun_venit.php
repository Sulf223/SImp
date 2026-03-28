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

                <div class="orbit orbit-merge">
                    <a href="index.php?page=sort_merge" class="planet planet-merge" data-stage="Explicare" title="Merge Sort">M</a>
                </div>

                <div class="orbit orbit-selection">
                    <a href="index.php?page=sort_selection" class="planet planet-selection" data-stage="Exersare" title="Selection Sort">S</a>
                </div>

                <div class="orbit orbit-quick">
                    <a href="index.php?page=sort_quick" class="planet planet-quick" data-stage="Maestrie" title="Quick Sort">Q</a>
                </div>
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
})();
</script>
