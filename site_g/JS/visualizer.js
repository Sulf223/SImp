/**
 * visualizer.js
 * 1) Pastreaza vizualizarea pentru metodele de sortare (pagina metoda)
 * 2) Adauga un laborator unificat (sortari + recursivitate + backtracking)
 */

/**
 * Clasa principala pentru vizualizarea algoritmilor de sortare pe Canvas.
 * Gestioneaza starea sirului, animatiile si interactiunea cu utilizatorul.
 */
class SortingVisualizer {
    /**
     * @param {string} containerId - ID-ul elementului DOM unde va fi randat canvas-ul.
     */
    constructor(containerId) {
        const el = document.getElementById(containerId);
        if (!el) return;

        this.algorithmName = (el.getAttribute("data-algorithm") || "bubble").toLowerCase();
        this.algorithmLabelMap = {
            bubble: "Bubble Sort",
            selection: "Selection Sort",
            insertion: "Insertion Sort",
            quick: "Quick Sort",
            merge: "Merge Sort",
            counting: "Counting Sort"
        };

        if (el.tagName.toLowerCase() === "canvas") {
            this.canvas = el;
            this.container = el.parentElement;
        } else {
            this.container = el;
            this.canvas = document.createElement("canvas");
            this.container.appendChild(this.canvas);
        }

        this.ctx = this.canvas.getContext("2d");

        this.array = [];
        this.valueLabels = null;
        this.size = 30;
        this.delay = 35;
        this.isSorting = false;

        this.comparisons = 0;
        this.swaps = 0;
        this.sortStartedAt = null;
        this.lastElapsedMs = 0;
        this.soundEnabled = false;
        this.audioContext = null;

        this.quizCurrentAlgorithm = null;
        this.lastRunAlgorithm = this.resolveAlgorithmName(this.algorithmName);

        const hasCustomControls = !!document.querySelector('[data-visualizer-controls="custom"]');
        if (!hasCustomControls) {
            this.initControls();
            this.createInfoPanels();
        } else {
            this.bindCustomControls();
        }
        
        this.onResize();
        this.resetArray();

        // FIX [A11]: ResizeObserver pentru robustețe
        if (typeof ResizeObserver !== 'undefined' && this.container) {
            this._resizeObserver = new ResizeObserver(() => this.onResize());
            this._resizeObserver.observe(this.container);
        }

        // FIX [A6]: Cleanup audio context on page unload
        window.addEventListener("beforeunload", () => this.destroy());

        // Pixel Perfect Hook: Hide skeleton and set global instance
        window.visualizerInstance = this;
        const skeleton = document.getElementById('skeleton-loader');
        if (skeleton) {
            setTimeout(() => {
                skeleton.style.opacity = '0';
                setTimeout(() => skeleton.style.display = 'none', 300);
            }, 800);
        }

        window.addEventListener("resize", () => this.onResize());
    }

    getFontFamily() {
        const root = getComputedStyle(document.documentElement);
        const sans = root.getPropertyValue('--font-sans').trim();
        return sans || 'Inter, system-ui, sans-serif';
    }

    /**
     * Schimba stilul liniei de cod active in pseudo-codul paginii.
     * @param {number} lineNumber - Numarul liniei de evidentiat.
     */
    highlightCodeLine(lineNumber) {
        const codeBlock = document.querySelector('[data-lesson-code]');
        if (!codeBlock) return;
        codeBlock.querySelectorAll('.code-line').forEach(el => el.classList.remove('is-active'));
        const line = codeBlock.querySelector(`[data-line="${lineNumber}"]`);
        if (line) line.classList.add('is-active');
    }
    
    /**
     * Actualizeaza valorile variabilelor urmarite in panoul de inspectie.
     * @param {Object} vars - Un obiect de tip { nume_variabila: valoare }.
     */
    updateVarInspector(vars) {
        const inspector = document.querySelector('[data-var-inspector]');
        if (!inspector) return;
        Object.entries(vars).forEach(([key, value]) => {
            const slot = inspector.querySelector(`[data-watch="${key}"]`);
            if (slot) slot.textContent = String(value);
        });
    }

    // Pixel Perfect Hook: Update external stats
    updateStatsUI(message) {
        const compEl = document.getElementById('comparisons');
        const swapEl = document.getElementById('swaps');
        const timeEl = document.getElementById('sort-time');
        const statusEl = document.getElementById('sort-status');

        const elapsed = this.sortStartedAt
            ? Math.max(0, Math.round(performance.now() - this.sortStartedAt))
            : this.lastElapsedMs;

        if (compEl) compEl.textContent = this.comparisons;
        if (swapEl) swapEl.textContent = this.swaps;
        if (timeEl) timeEl.textContent = elapsed + 'ms';
        if (statusEl) statusEl.textContent = this.formatRunStatus(message);
    }

    formatRunStatus(message) {
        const text = String(message || '').toLowerCase();
        if (text.includes('finalizata')) return 'Finalizat';
        if (text.includes('ruleaza')) return 'Ruleaza';
        if (text.includes('quiz')) return 'Quiz';
        if (text.includes('resetate') || text.includes('pregatit')) return 'Pregatit';
        if (this.isSorting) return 'Ruleaza';
        return 'Pregatit';
    }

    resolveAlgorithmName(name) {
        const lower = String(name || "").toLowerCase();
        if (lower.includes("bubble")) return "bubble";
        if (lower.includes("select")) return "selection";
        if (lower.includes("insert")) return "insertion";
        if (lower.includes("quick")) return "quick";
        if (lower.includes("merge") || lower.includes("interclasare")) return "merge";
        if (lower.includes("count")) return "counting";
        return "bubble";
    }

    formatAlgorithmName(name) {
        const key = this.resolveAlgorithmName(name);
        return this.algorithmLabelMap[key] || "Bubble Sort";
    }

    onResize() {
        if (!this.canvas || !this.container) return;
        const rect = this.canvas.getBoundingClientRect();
        this.canvas.width = rect.width || Math.max(this.container.clientWidth, 320);
        this.canvas.height = rect.height || 300;
        this.draw();
    }

    /**
     * Resurse cleanup.
     * FIX [A6]: Închide AudioContext și deconectează ResizeObserver.
     */
    destroy() {
        if (this.audioContext && this.audioContext.state !== 'closed') {
            this.audioContext.close().catch(() => {});
        }
        this.audioContext = null;

        if (this._resizeObserver) {
            this._resizeObserver.disconnect();
            this._resizeObserver = null;
        }
    }

    bindCustomControls() {
        const initialSizeControl = document.querySelector('[data-control="size"]');
        if (initialSizeControl) {
            this.size = parseInt(initialSizeControl.value, 10) || this.size;
        }

        const initialSpeedControl = document.querySelector('[data-control="speed"]');
        if (initialSpeedControl) {
            const val = initialSpeedControl.value;
            this.delay = val === 'slow' ? 80 : val === 'fast' ? 10 : 35;
        }

        document.querySelectorAll('[data-action="start"]').forEach(btn => {
            btn.addEventListener('click', () => this.runSort());
        });
        document.querySelectorAll('[data-action="regenerate"]').forEach(btn => {
            btn.addEventListener('click', () => this.resetArray());
        });
        document.querySelectorAll('[data-control="size"]').forEach(input => {
            input.addEventListener('change', e => {
                this.size = parseInt(e.target.value, 10);
                this.valueLabels = null;
                this.resetArray();
            });
        });
        document.querySelectorAll('[data-control="speed"]').forEach(input => {
            input.addEventListener('change', e => {
                const val = e.target.value;
                this.delay = val === 'slow' ? 80 : val === 'fast' ? 10 : 35;
            });
        });

        // Also create info panels if they don't exist but we are in custom mode
        // Usually custom mode pages have their own stats display but visualizer might need its meta
        if (!this.statsEl) {
            this.createInfoPanels();
        }
    }

    initControls() {
        const controlsMain = document.createElement("div");
        controlsMain.className = "visualizer-controls";

        const controlsAdvanced = document.createElement("div");
        controlsAdvanced.className = "visualizer-controls";

        const btnStart = document.createElement("button");
        btnStart.textContent = "Start vizualizare";
        btnStart.className = "btn btn-primary";
        btnStart.onclick = () => this.runSort();

        const btnReset = document.createElement("button");
        btnReset.textContent = "Genereaza sir nou";
        btnReset.className = "btn btn-ghost";
        btnReset.onclick = () => this.resetArray();

        const speedWrap = document.createElement("label");
        speedWrap.className = "viz-inline-label";
        speedWrap.textContent = "Viteza:";

        const speedInput = document.createElement("input");
        speedInput.type = "range";
        speedInput.min = "5";
        speedInput.max = "120";
        speedInput.step = "5";
        speedInput.value = String(this.delay);
        speedInput.oninput = () => {
            this.delay = parseInt(speedInput.value, 10);
        };
        speedWrap.appendChild(speedInput);

        const sizeWrap = document.createElement("label");
        sizeWrap.className = "viz-inline-label";
        sizeWrap.textContent = "Elemente:";

        const sizeInput = document.createElement("input");
        sizeInput.type = "range";
        sizeInput.min = "10";
        sizeInput.max = "90";
        sizeInput.step = "1";
        sizeInput.value = String(this.size);
        sizeInput.oninput = () => {
            if (this.isSorting) return;
            this.size = parseInt(sizeInput.value, 10);
            this.valueLabels = null;
            this.resetArray();
        };
        sizeWrap.appendChild(sizeInput);

        this.customInput = document.createElement("input");
        this.customInput.type = "text";
        this.customInput.className = "viz-custom-input";
        this.customInput.placeholder = "Input custom: 5,3,9 sau text";

        const btnApplyInput = document.createElement("button");
        btnApplyInput.className = "btn btn-ghost";
        btnApplyInput.textContent = "Aplica input";
        btnApplyInput.onclick = () => this.applyCustomInput();

        const btnBest = document.createElement("button");
        btnBest.className = "btn";
        btnBest.textContent = "Best";
        btnBest.onclick = () => this.generateCase("best");

        const btnWorst = document.createElement("button");
        btnWorst.className = "btn";
        btnWorst.textContent = "Worst";
        btnWorst.onclick = () => this.generateCase("worst");

        const btnAverage = document.createElement("button");
        btnAverage.className = "btn";
        btnAverage.textContent = "Average";
        btnAverage.onclick = () => this.generateCase("average");

        const soundWrap = document.createElement("label");
        soundWrap.className = "viz-inline-label";
        soundWrap.textContent = "Sunet";

        this.soundToggle = document.createElement("input");
        this.soundToggle.type = "checkbox";
        this.soundToggle.onchange = () => {
            this.soundEnabled = this.soundToggle.checked;
            this.updateStats(this.soundEnabled ? "Mod audio activ." : "Mod audio oprit.");
        };
        soundWrap.appendChild(this.soundToggle);

        const btnQuiz = document.createElement("button");
        btnQuiz.className = "btn btn-primary";
        btnQuiz.textContent = "Mod quiz";
        btnQuiz.onclick = () => this.startQuiz();

        this.quizSelect = document.createElement("select");
        this.quizSelect.className = "viz-select";
        this.quizSelect.innerHTML = [
            "<option value='bubble'>Bubble Sort</option>",
            "<option value='selection'>Selection Sort</option>",
            "<option value='insertion'>Insertion Sort</option>",
            "<option value='quick'>Quick Sort</option>",
            "<option value='merge'>Merge Sort</option>",
            "<option value='counting'>Counting Sort</option>"
        ].join("");

        const btnCheckQuiz = document.createElement("button");
        btnCheckQuiz.className = "btn";
        btnCheckQuiz.textContent = "Verifica raspuns";
        btnCheckQuiz.onclick = () => this.checkQuizAnswer();

        const btnExplain = document.createElement("button");
        btnExplain.className = "btn btn-ghost";
        btnExplain.textContent = "Explica-mi";
        btnExplain.onclick = () => this.explainCurrentAlgorithm();

        controlsMain.appendChild(btnStart);
        controlsMain.appendChild(btnReset);
        controlsMain.appendChild(speedWrap);
        controlsMain.appendChild(sizeWrap);
        controlsMain.appendChild(soundWrap);

        controlsAdvanced.appendChild(this.customInput);
        controlsAdvanced.appendChild(btnApplyInput);
        controlsAdvanced.appendChild(btnBest);
        controlsAdvanced.appendChild(btnWorst);
        controlsAdvanced.appendChild(btnAverage);
        controlsAdvanced.appendChild(btnQuiz);
        controlsAdvanced.appendChild(this.quizSelect);
        controlsAdvanced.appendChild(btnCheckQuiz);
        controlsAdvanced.appendChild(btnExplain);

        this.container.appendChild(controlsMain);
        this.container.appendChild(controlsAdvanced);
    }

    createInfoPanels() {
        this.statsEl = document.createElement("div");
        this.statsEl.className = "viz-meta";
        this.container.appendChild(this.statsEl);

        this.explainPanel = document.createElement("div");
        this.explainPanel.className = "viz-panel viz-explain";
        this.explainPanel.innerHTML = "<div class='step-log'>Apasa \"Explica-mi\" pentru explicatii AI in romana.</div>";
        this.container.appendChild(this.explainPanel);
    }

    resetCounters() {
        this.comparisons = 0;
        this.swaps = 0;
        this.sortStartedAt = null;
        this.lastElapsedMs = 0;
        this.updateStats("Contoare resetate.");
    }

    updateStats(message) {
        this.updateStatsUI(message);
        if (!this.statsEl) return;
        const algorithm = this.formatAlgorithmName(this.lastRunAlgorithm || this.algorithmName);
        this.statsEl.innerHTML = "<strong>Algoritm:</strong> " + algorithm +
            " <span>|</span> <strong>Comparatii:</strong> " + this.comparisons +
            " <span>|</span> <strong>Swap-uri:</strong> " + this.swaps +
            (message ? " <span>|</span> " + message : "");
    }

    ensureAudioContext() {
        if (!this.audioContext) {
            const Ctx = window.AudioContext || window.webkitAudioContext;
            if (Ctx) {
                this.audioContext = new Ctx();
            }
        }
    }

    playTone(value, kind) {
        if (!this.soundEnabled) return;
        // FIX [A16]: prefers-reduced-motion check
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        this.ensureAudioContext();
        if (!this.audioContext) return;

        if (this.audioContext.state === "suspended") {
            this.audioContext.resume();
        }

        const osc = this.audioContext.createOscillator();
        const gain = this.audioContext.createGain();
        const freq = 140 + Math.max(0, Math.min(900, Number(value || 0) * 8));

        osc.type = kind === "swap" ? "square" : "sine";
        osc.frequency.value = freq;
        gain.gain.value = kind === "swap" ? 0.03 : 0.018;

        osc.connect(gain);
        gain.connect(this.audioContext.destination);
        osc.start();
        osc.stop(this.audioContext.currentTime + (kind === "swap" ? 0.04 : 0.02));
    }

    registerComparison(a, b) {
        this.comparisons++;
        this.playTone(Math.round((Math.abs(a) + Math.abs(b)) / 2), "compare");
        this.updateStats();
    }

    registerSwap(a, b) {
        this.swaps++;
        this.playTone(Math.round((Math.abs(a) + Math.abs(b)) / 2), "swap");
        this.updateStats();
    }

    resetArray() {
        if (this.isSorting) return;
        this.array = [];
        this.valueLabels = null;
        for (let i = 0; i < this.size; i++) {
            this.array.push(Math.floor(Math.random() * 90) + 10);
        }
        this.resetCounters();
        this.draw();
    }

    applyCustomInput() {
        if (this.isSorting) return;
        const raw = String(this.customInput.value || "").trim();
        if (!raw) {
            this.updateStats("Introdu un sir de numere sau text.");
            return;
        }

        const numericTokens = raw.match(/-?\d+/g);
        if (numericTokens && numericTokens.length >= 2) {
            const numbers = numericTokens.slice(0, 120).map(n => Math.max(-999, Math.min(999, parseInt(n, 10))));
            this.array = numbers;
            this.valueLabels = null;
            this.size = numbers.length;
            this.resetCounters();
            this.draw();
            this.updateStats("Input numeric personalizat aplicat.");
            return;
        }

        const text = raw.replace(/\s+/g, "");
        if (text.length < 2) {
            this.updateStats("Inputul trebuie sa aiba cel putin 2 elemente.");
            return;
        }

        const chars = text.slice(0, 50).split("");
        this.array = chars.map(ch => ch.charCodeAt(0));
        this.valueLabels = chars;
        this.size = this.array.length;
        this.resetCounters();
        this.draw();
        this.updateStats("Input text personalizat aplicat (ordonare alfabetica prin cod ASCII).");
    }

    generateCase(type) {
        if (this.isSorting) return;
        const n = Math.max(8, this.size || 30);
        const algo = this.resolveAlgorithmName(this.algorithmName);
        let arr = [];

        if (type === "average") {
            arr = this.makeRandomArray(n, 10, 99);
        } else if (type === "best") {
            if (algo === "quick") {
                arr = this.makeRandomArray(n, 10, 99);
            } else {
                arr = this.makeRandomArray(n, 10, 99).sort((a, b) => a - b);
            }
        } else {
            if (algo === "quick") {
                arr = this.makeRandomArray(n, 10, 99).sort((a, b) => a - b);
            } else {
                arr = this.makeRandomArray(n, 10, 99).sort((a, b) => b - a);
            }
        }

        this.array = arr;
        this.valueLabels = null;
        this.size = arr.length;
        this.resetCounters();
        this.draw();
        this.updateStats("Dataset " + type.toUpperCase() + " generat pentru " + this.formatAlgorithmName(algo) + ".");
    }

    makeRandomArray(size, minValue, maxValue) {
        const arr = [];
        for (let i = 0; i < size; i++) {
            arr.push(Math.floor(Math.random() * (maxValue - minValue + 1)) + minValue);
        }
        return arr;
    }

    draw(highlightIndices = [], pivotIndex = -1, sortedTail = -1) {
        const style = getComputedStyle(document.documentElement);
        const colors = {
            primary: (style.getPropertyValue('--color-primary').trim() || "#6E56CF"),
            success: (style.getPropertyValue('--color-success').trim() || "#10B981"),
            warning: (style.getPropertyValue('--color-warning').trim() || "#F59E0B"),
            danger:  (style.getPropertyValue('--color-danger').trim()  || "#EF4444"),
            fg:      (style.getPropertyValue('--color-fg').trim()      || "#F4F4F5")
        };

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        if (!this.array.length) return;

        const length = this.array.length;
        const barWidth = this.canvas.width / length;
        const minVal = Math.min(...this.array);
        const maxVal = Math.max(...this.array);
        const range = Math.max(1, maxVal - minVal + 1);

        for (let i = 0; i < length; i++) {
            const value = this.array[i];
            const normalized = value - minVal + 1;
            const barHeight = (normalized / range) * this.canvas.height;

            if (i >= sortedTail && sortedTail !== -1) {
                this.ctx.fillStyle = colors.success;
            } else if (pivotIndex === i) {
                this.ctx.fillStyle = colors.warning;
            } else if (highlightIndices.includes(i)) {
                this.ctx.fillStyle = colors.danger;
            } else {
                this.ctx.fillStyle = colors.primary;
            }

            const x = i * barWidth;
            const y = this.canvas.height - barHeight;
            const w = Math.max(1, barWidth - 2);
            this.ctx.fillRect(x, y, w, barHeight);

            if (this.valueLabels && this.valueLabels[i] && length <= 35) {
                this.ctx.fillStyle = colors.fg;
                this.ctx.font = `11px ${this.getFontFamily()}`;
                this.ctx.textAlign = "center";
                this.ctx.fillText(this.valueLabels[i], x + w / 2, Math.max(12, y - 4));
            }
        }
    }

    async runSort(forcedAlgorithm, quizMode) {
        if (this.isSorting) return;
        this.isSorting = true;

        const activeAlgorithm = this.resolveAlgorithmName(forcedAlgorithm || this.algorithmName);
        this.lastRunAlgorithm = activeAlgorithm;
        this.resetCounters();
        this.sortStartedAt = performance.now();
        this.updateStats("Ruleaza animatia...");

        if (activeAlgorithm === "bubble") await this.bubbleSort();
        else if (activeAlgorithm === "selection") await this.selectionSort();
        else if (activeAlgorithm === "insertion") await this.insertionSort();
        else if (activeAlgorithm === "quick") await this.quickSort(0, this.array.length - 1);
        else if (activeAlgorithm === "merge") await this.mergeSort(0, this.array.length - 1);
        else if (activeAlgorithm === "counting") await this.countingSort();
        else await this.bubbleSort();

        this.draw([], -1, 0);
        this.isSorting = false;
        this.lastElapsedMs = this.sortStartedAt ? Math.round(performance.now() - this.sortStartedAt) : this.lastElapsedMs;
        this.sortStartedAt = null;

        if (quizMode) {
            this.updateStats("Quiz: ghiceste algoritmul si apasa Verifica raspuns.");
        } else {
            this.updateStats("Sortare finalizata.");
        }
    }

    startQuiz() {
        if (this.isSorting) return;
        const options = ["bubble", "selection", "insertion", "quick", "merge", "counting"];
        const index = Math.floor(Math.random() * options.length);
        this.quizCurrentAlgorithm = options[index];
        this.resetArray();
        this.updateStats("Quiz pornit: priveste animatia si ghiceste algoritmul.");
        this.runSort(this.quizCurrentAlgorithm, true);
    }

    checkQuizAnswer() {
        if (!this.quizCurrentAlgorithm) {
            this.updateStats("Porneste mai intai Mod quiz.");
            return;
        }

        const guess = this.resolveAlgorithmName(this.quizSelect.value);
        if (guess === this.quizCurrentAlgorithm) {
            this.updateStats("Corect! Ai ghicit: " + this.formatAlgorithmName(this.quizCurrentAlgorithm) + ".");
        } else {
            this.updateStats("Nu inca. Raspuns corect: " + this.formatAlgorithmName(this.quizCurrentAlgorithm) + ".");
        }
        this.quizCurrentAlgorithm = null;
    }

    async explainCurrentAlgorithm() {
        const algorithm = this.formatAlgorithmName(this.lastRunAlgorithm || this.algorithmName);
        const prompt = "Explica in romana, clar si pe scurt, cum functioneaza " + algorithm +
            ". Include: idee, complexitate, cand este bun/slab, si aplica pe exemplul curent. " +
            "Avem " + this.comparisons + " comparatii si " + this.swaps + " swap-uri in ultima rulare.";

        this.explainPanel.innerHTML = "<div class='step-log'>Generez explicatia AI...</div>";

        try {
            const response = await fetch("PHP/profesor_ai_chat.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ message: prompt, history: [] })
            });

            const data = await response.json();
            if (!response.ok || !data.ok) {
                const err = (data && data.error) ? data.error : "Eroare la explicatia AI.";
                this.explainPanel.innerHTML = "<div class='step-log'>" + this.escapeHtml(err) + "</div>";
                return;
            }

            this.explainPanel.innerHTML = "<div class='step-log'>" + this.escapeHtml(String(data.reply || "")) + "</div>";
        } catch (error) {
            this.explainPanel.innerHTML = "<div class='step-log'>Nu am putut contacta serviciul AI. Incearca din nou.</div>";
        }
    }

    escapeHtml(value) {
        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/\n/g, "<br>");
    }

    sleep() {
        return new Promise(resolve => setTimeout(resolve, this.delay));
    }

    async bubbleSort() {
        const len = this.array.length;
        for (let i = 0; i < len; i++) {
            this.highlightCodeLine(1);
            this.updateVarInspector({ i, j: '—', comparisons: this.comparisons, swaps: this.swaps });
            for (let j = 0; j < len - i - 1; j++) {
                this.highlightCodeLine(2);
                this.updateVarInspector({ i, j, comparisons: this.comparisons, swaps: this.swaps });
                this.registerComparison(this.array[j], this.array[j + 1]);
                this.draw([j, j + 1], -1, len - i);
                await this.sleep();
                if (this.array[j] > this.array[j + 1]) {
                    this.highlightCodeLine(3);
                    this.registerSwap(this.array[j], this.array[j + 1]);
                    [this.array[j], this.array[j + 1]] = [this.array[j + 1], this.array[j]];
                    this.highlightCodeLine(4);
                    this.updateVarInspector({ i, j, comparisons: this.comparisons, swaps: this.swaps });
                    this.draw([j, j + 1], -1, len - i);
                    await this.sleep();
                }
            }
        }
        this.highlightCodeLine(0);
    }

    async selectionSort() {
        const len = this.array.length;
        for (let i = 0; i < len; i++) {
            this.highlightCodeLine(1);
            let min = i;
            this.highlightCodeLine(2);
            this.updateVarInspector({ i, j: '—', minIdx: min, comparisons: this.comparisons, swaps: this.swaps });
            for (let j = i + 1; j < len; j++) {
                this.highlightCodeLine(3);
                this.updateVarInspector({ i, j, minIdx: min, comparisons: this.comparisons, swaps: this.swaps });
                this.registerComparison(this.array[j], this.array[min]);
                this.draw([i, j], min);
                await this.sleep();
                if (this.array[j] < this.array[min]) {
                    min = j;
                    this.highlightCodeLine(4);
                    this.updateVarInspector({ i, j, minIdx: min, comparisons: this.comparisons, swaps: this.swaps });
                }
            }
            if (min !== i) {
                this.highlightCodeLine(5);
                this.registerSwap(this.array[i], this.array[min]);
                [this.array[i], this.array[min]] = [this.array[min], this.array[i]];
                this.draw([i, min], min);
                await this.sleep();
            }
        }
        this.highlightCodeLine(0);
    }

    async insertionSort() {
        const len = this.array.length;
        for (let i = 1; i < len; i++) {
            this.highlightCodeLine(1);
            const key = this.array[i];
            this.highlightCodeLine(2);
            let j = i - 1;
            this.highlightCodeLine(3);
            this.updateVarInspector({ i, j, key, comparisons: this.comparisons, swaps: this.swaps });
            while (j >= 0) {
                this.highlightCodeLine(4);
                this.registerComparison(this.array[j], key);
                this.draw([j, j + 1]);
                await this.sleep();
                if (this.array[j] > key) {
                    this.highlightCodeLine(5);
                    this.registerSwap(this.array[j], key);
                    this.array[j + 1] = this.array[j];
                    j--;
                    this.updateVarInspector({ i, j, key, comparisons: this.comparisons, swaps: this.swaps });
                } else {
                    break;
                }
            }
            this.highlightCodeLine(6);
            this.array[j + 1] = key;
            this.draw([j + 1]);
            await this.sleep();
        }
        this.highlightCodeLine(0);
    }

    async quickSort(start, end) {
        if (start >= end) return;
        const index = await this.partition(start, end);
        await this.quickSort(start, index - 1);
        await this.quickSort(index + 1, end);
        this.highlightCodeLine(0);
    }

    async partition(start, end) {
        this.highlightCodeLine(1);
        const pivotValue = this.array[end];
        this.highlightCodeLine(2);
        let pivotIndex = start;
        this.updateVarInspector({ low: start, high: end, pivot: pivotValue, i: '—', comparisons: this.comparisons, swaps: this.swaps });
        for (let i = start; i < end; i++) {
            this.highlightCodeLine(3);
            this.updateVarInspector({ low: start, high: end, pivot: pivotValue, i, comparisons: this.comparisons, swaps: this.swaps });
            this.registerComparison(this.array[i], pivotValue);
            this.draw([i, end], pivotIndex);
            await this.sleep();
            if (this.array[i] < pivotValue) {
                this.highlightCodeLine(4);
                this.registerSwap(this.array[i], this.array[pivotIndex]);
                [this.array[i], this.array[pivotIndex]] = [this.array[pivotIndex], this.array[i]];
                pivotIndex++;
                this.updateVarInspector({ low: start, high: end, pivot: pivotValue, i, comparisons: this.comparisons, swaps: this.swaps });
            }
        }
        this.highlightCodeLine(5);
        this.registerSwap(this.array[pivotIndex], this.array[end]);
        [this.array[pivotIndex], this.array[end]] = [this.array[end], this.array[pivotIndex]];
        this.draw([pivotIndex, end], pivotIndex);
        await this.sleep();
        this.highlightCodeLine(6);
        return pivotIndex;
    }

    async mergeSort(start, end) {
        if (start >= end) return;
        const mid = Math.floor((start + end) / 2);
        await this.mergeSort(start, mid);
        await this.mergeSort(mid + 1, end);
        await this.merge(start, mid, end);
        this.highlightCodeLine(0);
    }

    async merge(start, mid, end) {
        this.highlightCodeLine(1);
        const left = this.array.slice(start, mid + 1);
        const right = this.array.slice(mid + 1, end + 1);
        this.highlightCodeLine(2);
        let i = 0;
        let j = 0;
        let k = start;
        this.updateVarInspector({ lo: start, mid, hi: end, i, j, k, comparisons: this.comparisons, swaps: this.swaps });

        while (i < left.length && j < right.length) {
            this.highlightCodeLine(3);
            this.updateVarInspector({ lo: start, mid, hi: end, i, j, k, comparisons: this.comparisons, swaps: this.swaps });
            this.registerComparison(left[i], right[j]);
            this.draw([k]);
            await this.sleep();
            if (left[i] <= right[j]) {
                this.highlightCodeLine(4);
                this.array[k++] = left[i++];
            } else {
                this.highlightCodeLine(5);
                this.array[k++] = right[j++];
            }
            this.updateVarInspector({ lo: start, mid, hi: end, i, j, k, comparisons: this.comparisons, swaps: this.swaps });
        }

        while (i < left.length) {
            this.array[k++] = left[i++];
            this.draw([k - 1]);
            await this.sleep();
        }

        while (j < right.length) {
            this.array[k++] = right[j++];
            this.draw([k - 1]);
            await this.sleep();
        }
    }

    async countingSort() {
        const min = Math.min(...this.array);
        const max = Math.max(...this.array);
        const offset = min < 0 ? -min : 0;
        const count = new Array(max + offset + 1).fill(0);

        for (let i = 0; i < this.array.length; i++) {
            this.highlightCodeLine(1);
            this.updateVarInspector({ i, value: '—', idx: '—', comparisons: this.comparisons, swaps: this.swaps });
            count[this.array[i] + offset]++;
            this.draw([i]);
            await this.sleep();
        }

        this.highlightCodeLine(2);
        let idx = 0;
        for (let v = 0; v < count.length; v++) {
            this.highlightCodeLine(3);
            this.updateVarInspector({ i: '—', value: v - offset, idx, comparisons: this.comparisons, swaps: this.swaps });
            while (count[v] > 0) {
                this.highlightCodeLine(4);
                this.array[idx] = v - offset;
                this.draw([idx]);
                await this.sleep();
                idx++;
                count[v]--;
                this.updateVarInspector({ i: '—', value: v - offset, idx, comparisons: this.comparisons, swaps: this.swaps });
            }
        }
        this.highlightCodeLine(0);
    }
}

class AlgorithmLab {
    constructor(container) {
        this.container = container;
        // Clear container to prevent duplicate UI (fix "canvas dublat" risk)
        this.container.innerHTML = "";
        
        this.steps = [];
        this.stepIndex = 0;
        this.timer = null;
        this.running = false;
        this.algorithmLabels = {
            bubble: "Bubble Sort",
            selection: "Selection Sort",
            insertion: "Insertion Sort",
            quick: "Quick Sort",
            merge: "Merge Sort",
            counting: "Counting Sort",
            factorial: "Recursivitate: Factorial",
            fibonacci: "Recursivitate: Fibonacci",
            permutari: "Backtracking: Permutări"
        };
        this.actionLabels = {
            init: "Se inițializează",
            compare: "Se compară",
            swap: "Se interschimbă",
            scan: "Se caută",
            insert: "Se inserează",
            pivot: "Se alege pivotul",
            partition: "Se partiționează",
            split: "Se împarte",
            merge: "Se interclasează",
            count: "Se numără",
            place: "Se plasează",
            call: "Se apelează funcția",
            base: "Caz de bază",
            returns: "Se întoarce rezultatul",
            choose: "Se alege o valoare",
            prune: "Se elimină o variantă",
            backtrack: "Se revine",
            solution: "Soluție găsită",
            done: "Final"
        };

        this.buildLayout();
        this.generateScenario();
        
        // Initial resize
        setTimeout(() => this.onResize(), 50);
        window.addEventListener("resize", () => this.onResize());
    }

    getFontFamily() {
        const root = getComputedStyle(document.documentElement);
        const sans = (root.getPropertyValue('--font-sans') || 'Inter').trim();
        return sans + ', system-ui, sans-serif';
    }

    getAlgorithmLabel(key) {
        return this.algorithmLabels[key] || key;
    }

    getActionLabel(key) {
        return this.actionLabels[key] || "Pas";
    }

    escapeHTML(value) {
        return String(value ?? "")
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    buildLayout() {
        this.controls = document.createElement("div");
        this.controls.className = "visualizer-controls";

        this.algorithmSelect = document.createElement("select");
        this.algorithmSelect.className = "viz-select";
        this.algorithmSelect.setAttribute("aria-label", "Alege algoritmul");
        this.algorithmSelect.innerHTML = Object.entries(this.algorithmLabels)
            .map(([value, label]) => `<option value="${value}">${label}</option>`)
            .join("");
        this.algorithmSelect.value = this.container.getAttribute("data-default") || "bubble";

        this.inputN = document.createElement("input");
        this.inputN.type = "number";
        this.inputN.min = "3";
        this.inputN.max = "9";
        this.inputN.value = "6";
        this.inputN.className = "viz-input";
        this.inputN.style.width = "60px";
        this.inputN.setAttribute("aria-label", "Dimensiunea scenariului");

        this.presetSelect = document.createElement("select");
        this.presetSelect.className = "viz-select";
        this.presetSelect.setAttribute("aria-label", "Preset pentru date");
        this.presetSelect.innerHTML = [
            "<option value='random'>Preset: aleator</option>",
            "<option value='nearly'>Preset: aproape sortat</option>",
            "<option value='reverse'>Preset: invers sortat</option>",
            "<option value='duplicates'>Preset: cu duplicate</option>",
            "<option value='sorted'>Preset: deja sortat</option>"
        ].join("");
        this.presetSelect.addEventListener("change", () => this.generateScenario());

        this.btnGenerate = document.createElement("button");
        this.btnGenerate.className = "btn btn--ghost btn--sm";
        this.btnGenerate.textContent = "Generează scenariu";
        this.btnGenerate.onclick = () => this.generateScenario();

        this.btnPrev = document.createElement("button");
        this.btnPrev.className = "btn btn--ghost btn--sm";
        this.btnPrev.textContent = "Pas anterior";
        this.btnPrev.onclick = () => this.stepBack();

        this.btnStep = document.createElement("button");
        this.btnStep.className = "btn btn--sm";
        this.btnStep.textContent = "Pas următor";
        this.btnStep.onclick = () => this.stepForward();

        this.btnPlay = document.createElement("button");
        this.btnPlay.className = "btn btn--primary btn--sm";
        this.btnPlay.textContent = "Rulează";
        this.btnPlay.onclick = () => this.togglePlay();

        this.btnReset = document.createElement("button");
        this.btnReset.className = "btn btn--quiet btn--sm";
        this.btnReset.textContent = "Reset";
        this.btnReset.onclick = () => this.resetScenario();

        this.speedSelect = document.createElement("select");
        this.speedSelect.className = "viz-select";
        this.speedSelect.setAttribute("aria-label", "Viteza redării");
        this.speedSelect.innerHTML = "<option value='700'>Viteză: lent</option><option value='380' selected>Viteză: mediu</option><option value='180'>Viteză: rapid</option>";

        const algorithmLabel = document.createElement("label");
        algorithmLabel.className = "viz-inline-label";
        algorithmLabel.append("Algoritm", this.algorithmSelect);

        const sizeLabel = document.createElement("label");
        sizeLabel.className = "viz-inline-label";
        sizeLabel.append("Dimensiune", this.inputN);

        this.controls.appendChild(algorithmLabel);
        this.controls.appendChild(sizeLabel);
        this.controls.appendChild(this.presetSelect);
        this.controls.appendChild(this.btnGenerate);
        this.controls.appendChild(this.btnPrev);
        this.controls.appendChild(this.btnStep);
        this.controls.appendChild(this.btnPlay);
        this.controls.appendChild(this.btnReset);
        this.controls.appendChild(this.speedSelect);

        this.meta = document.createElement("div");
        this.meta.className = "viz-meta";

        this.legend = document.createElement("div");
        this.legend.className = "viz-legend";
        this.legend.innerHTML = [
            ["normal", "Element normal"],
            ["active", "Comparație / mutare"],
            ["pivot", "Pivot / apel activ"],
            ["solution", "Soluție găsită"]
        ].map(([key, label]) => (
            `<span class="viz-legend__item"><span class="viz-legend__swatch viz-legend__swatch--${key}"></span>${label}</span>`
        )).join("");

        this.explain = document.createElement("section");
        this.explain.className = "viz-current-explain";

        this.canvas = document.createElement("canvas");
        this.canvas.height = 320;
        this.ctx = this.canvas.getContext("2d");

        this.pseudocodePanel = document.createElement("aside");
        this.pseudocodePanel.className = "viz-pseudocode";

        this.quizPanel = document.createElement("aside");
        this.quizPanel.className = "viz-guess";
        this.quizPanel.addEventListener("click", e => {
            const btn = e.target.closest("[data-guess-action]");
            if (!btn) return;
            this.handleGuess(btn.getAttribute("data-guess-action"));
        });

        this.stage = document.createElement("div");
        this.stage.className = "viz-stage";
        this.stage.appendChild(this.canvas);

        this.sidePanel = document.createElement("div");
        this.sidePanel.className = "viz-side-panel";
        this.sidePanel.appendChild(this.pseudocodePanel);
        this.sidePanel.appendChild(this.quizPanel);

        this.mainGrid = document.createElement("div");
        this.mainGrid.className = "viz-main-grid";
        this.mainGrid.appendChild(this.stage);
        this.mainGrid.appendChild(this.sidePanel);

        this.panel = document.createElement("div");
        this.panel.className = "viz-panel";

        this.container.appendChild(this.controls);
        this.container.appendChild(this.legend);
        this.container.appendChild(this.meta);
        this.container.appendChild(this.explain);
        this.container.appendChild(this.mainGrid);
        this.container.appendChild(this.panel);

        this.algorithmSelect.addEventListener("change", () => this.generateScenario());
    }

    onResize() {
        if (!this.canvas || !this.container) return;
        const rect = this.container.getBoundingClientRect();
        this.canvas.width = Math.max(rect.width - 40, 320);
        this.render();
    }

    generateScenario() {
        this.stop();
        this.stepIndex = 0;

        const algo = this.algorithmSelect.value;
        const nRaw = Number(this.inputN.value || 6);
        const n = Math.max(3, Math.min(9, nRaw));
        this.inputN.value = String(n);
        const isSorting = ["bubble", "selection", "insertion", "quick", "merge", "counting"].includes(algo);
        this.presetSelect.disabled = !isSorting;

        if (isSorting) {
            const size = Math.max(5, Math.min(24, n * 2));
            const arr = this.makeScenarioArray(size, this.presetSelect.value);
            this.steps = this.buildSortingSteps(algo, arr);
        } else if (algo === "factorial") {
            this.steps = this.buildFactorialSteps(Math.min(n, 8));
        } else if (algo === "fibonacci") {
            this.steps = this.buildFibonacciSteps(Math.min(n, 8));
        } else {
            const backtrackingSize = Math.min(n, 3);
            this.inputN.value = String(backtrackingSize);
            this.steps = this.buildPermutationSteps(backtrackingSize);
        }

        this.onResize();
        this.render();
    }

    resetScenario() {
        this.stop();
        this.stepIndex = 0;
        this.render();
    }

    makeRandomArray(size) {
        const arr = [];
        for (let i = 0; i < size; i++) {
            arr.push(Math.floor(Math.random() * 90) + 10);
        }
        return arr;
    }

    makeScenarioArray(size, preset) {
        const random = this.makeRandomArray(size);
        const sorted = [...random].sort((a, b) => a - b);

        if (preset === "sorted") {
            return sorted;
        }

        if (preset === "reverse") {
            return [...sorted].reverse();
        }

        if (preset === "nearly") {
            const arr = [...sorted];
            if (arr.length > 3) {
                const a = Math.floor(arr.length / 3);
                const b = Math.min(arr.length - 1, a + 2);
                [arr[a], arr[b]] = [arr[b], arr[a]];
            }
            return arr;
        }

        if (preset === "duplicates") {
            const values = [18, 24, 24, 36, 36, 48, 60, 60, 72];
            return Array.from({ length: size }, (_, i) => values[i % values.length]);
        }

        return random;
    }

    buildSortingSteps(algo, source) {
        const arr = [...source];
        const steps = [];
        const push = (message, highlight = [], pivot = -1, line = 0, action = "scan") => {
            steps.push({
                kind: "sorting",
                algo,
                title: this.getActionLabel(action),
                message,
                array: [...arr],
                highlight,
                pivot,
                line,
                action
            });
        };

        push("Stare inițială", [], -1, 1, "init");

        if (algo === "bubble") {
            for (let i = 0; i < arr.length; i++) {
                for (let j = 0; j < arr.length - i - 1; j++) {
                    push(`Comparăm ${arr[j]} și ${arr[j + 1]}. Dacă primul este mai mare, le interschimbăm.`, [j, j + 1], -1, 3, "compare");
                    if (arr[j] > arr[j + 1]) {
                        [arr[j], arr[j + 1]] = [arr[j + 1], arr[j]];
                        push(`Interschimbăm elementele de pe pozițiile ${j} și ${j + 1}.`, [j, j + 1], -1, 5, "swap");
                    }
                }
            }
        } else if (algo === "selection") {
            for (let i = 0; i < arr.length; i++) {
                let min = i;
                for (let j = i + 1; j < arr.length; j++) {
                    push(`Căutăm minimul pentru poziția ${i}: comparăm v[${j}] cu minimul curent.`, [i, j], min, 4, "compare");
                    if (arr[j] < arr[min]) {
                        min = j;
                        push(`Actualizăm poziția minimului: min devine ${j}.`, [j], min, 5, "scan");
                    }
                }
                if (min !== i) {
                    [arr[i], arr[min]] = [arr[min], arr[i]];
                    push(`Mutăm minimul găsit pe poziția ${i}.`, [i, min], min, 7, "swap");
                }
            }
        } else if (algo === "insertion") {
            for (let i = 1; i < arr.length; i++) {
                const key = arr[i];
                let j = i - 1;
                push(`Alegem cheia ${key} și o inserăm în partea deja sortată.`, [i], -1, 2, "scan");
                while (j >= 0 && arr[j] > key) {
                    arr[j + 1] = arr[j];
                    push(`Mutăm ${arr[j]} spre dreapta pentru a face loc cheii.`, [j, j + 1], -1, 5, "swap");
                    j--;
                }
                arr[j + 1] = key;
                push(`Inserăm cheia ${key} pe poziția ${j + 1}.`, [j + 1], -1, 7, "insert");
            }
        } else if (algo === "quick") {
            const quick = (lo, hi) => {
                if (lo >= hi) return;
                const pivot = arr[hi];
                let p = lo;
                push(`Alegem pivotul ${pivot} pentru segmentul [${lo}, ${hi}].`, [hi], hi, 2, "pivot");
                for (let i = lo; i < hi; i++) {
                    push(`Comparăm ${arr[i]} cu pivotul ${pivot}. Elementele mai mici merg în stânga.`, [i, hi], p, 5, "compare");
                    if (arr[i] < pivot) {
                        [arr[i], arr[p]] = [arr[p], arr[i]];
                        push(`Mutăm elementul ${arr[p]} în zona mai mică decât pivotul.`, [i, p], p, 6, "partition");
                        p++;
                    }
                }
                [arr[p], arr[hi]] = [arr[hi], arr[p]];
                push(`Fixăm pivotul pe poziția finală ${p}.`, [p, hi], p, 8, "swap");
                quick(lo, p - 1);
                quick(p + 1, hi);
            };
            quick(0, arr.length - 1);
        } else if (algo === "merge") {
            const merge = (lo, mid, hi) => {
                const left = arr.slice(lo, mid + 1);
                const right = arr.slice(mid + 1, hi + 1);
                let i = 0;
                let j = 0;
                let k = lo;
                while (i < left.length && j < right.length) {
                    if (left[i] <= right[j]) arr[k++] = left[i++];
                    else arr[k++] = right[j++];
                    push(`Alegem cel mai mic element dintre cele două jumătăți și îl punem pe poziția ${k - 1}.`, [k - 1], -1, 6, "merge");
                }
                while (i < left.length) {
                    arr[k++] = left[i++];
                    push(`Copiem restul din jumătatea stângă pe poziția ${k - 1}.`, [k - 1], -1, 7, "place");
                }
                while (j < right.length) {
                    arr[k++] = right[j++];
                    push(`Copiem restul din jumătatea dreaptă pe poziția ${k - 1}.`, [k - 1], -1, 8, "place");
                }
            };
            const rec = (lo, hi) => {
                if (lo >= hi) return;
                const mid = Math.floor((lo + hi) / 2);
                push(`Împărțim segmentul [${lo}, ${hi}] în [${lo}, ${mid}] și [${mid + 1}, ${hi}].`, [], -1, 2, "split");
                rec(lo, mid);
                rec(mid + 1, hi);
                merge(lo, mid, hi);
            };
            rec(0, arr.length - 1);
        } else {
            let max = Math.max(...arr);
            const freq = new Array(max + 1).fill(0);
            for (let i = 0; i < arr.length; i++) {
                freq[arr[i]]++;
                push(`Creștem frecvența valorii ${arr[i]}.`, [i], -1, 3, "count");
            }
            let pos = 0;
            for (let value = 0; value < freq.length; value++) {
                while (freq[value] > 0) {
                    arr[pos] = value;
                    push(`Plasăm valoarea ${value} pe poziția ${pos}.`, [pos], -1, 6, "place");
                    pos++;
                    freq[value]--;
                }
            }
        }

        push("Sortare finalizată", [], -1, 0, "done");
        return steps;
    }

    buildFactorialSteps(n) {
        const steps = [];
        const stack = [];

        const rec = x => {
            stack.push(`fact(${x})`);
            steps.push({
                kind: "stack",
                algo: "factorial",
                title: `Apel fact(${x})`,
                message: `Intrăm în apelul fact(${x})`,
                stack: [...stack],
                output: null,
                line: 1,
                action: "call"
            });

            if (x === 0) {
                steps.push({
                    kind: "stack",
                    algo: "factorial",
                    title: "Caz de bază",
                    message: "n == 0, returnăm 1",
                    stack: [...stack],
                    output: "return 1",
                    line: 3,
                    action: "base"
                });
                stack.pop();
                return 1;
            }

            const result = x * rec(x - 1);
            steps.push({
                kind: "stack",
                algo: "factorial",
                title: `Întoarcere din fact(${x})`,
                message: `Calculăm ${x} * fact(${x - 1}) = ${result}`,
                stack: [...stack],
                output: `return ${result}`,
                line: 5,
                action: "returns"
            });
            stack.pop();
            return result;
        };

        const finalValue = rec(n);
        steps.push({
            kind: "stack",
            algo: "factorial",
            title: "Rezultat final",
            message: `factorial(${n}) = ${finalValue}`,
            stack: [],
            output: String(finalValue),
            line: 0,
            action: "done"
        });
        return steps;
    }

    buildFibonacciSteps(n) {
        const steps = [];
        const stack = [];

        const rec = x => {
            stack.push(`fib(${x})`);
            steps.push({
                kind: "stack",
                algo: "fibonacci",
                title: `Apel fib(${x})`,
                message: `Intrăm în fib(${x})`,
                stack: [...stack],
                output: null,
                line: 1,
                action: "call"
            });

            if (x <= 1) {
                steps.push({
                    kind: "stack",
                    algo: "fibonacci",
                    title: "Caz de bază",
                    message: `fib(${x}) = ${x}`,
                    stack: [...stack],
                    output: `return ${x}`,
                    line: 3,
                    action: "base"
                });
                stack.pop();
                return x;
            }

            const a = rec(x - 1);
            const b = rec(x - 2);
            const sum = a + b;

            steps.push({
                kind: "stack",
                algo: "fibonacci",
                title: `Combinăm rezultatele`,
                message: `fib(${x - 1}) + fib(${x - 2}) = ${a} + ${b} = ${sum}`,
                stack: [...stack],
                output: `return ${sum}`,
                line: 6,
                action: "returns"
            });
            stack.pop();
            return sum;
        };

        const finalValue = rec(n);
        steps.push({
            kind: "stack",
            algo: "fibonacci",
            title: "Rezultat final",
            message: `fib(${n}) = ${finalValue}`,
            stack: [],
            output: String(finalValue),
            line: 0,
            action: "done"
        });

        return steps;
    }

    buildPermutationSteps(n) {
        const steps = [];
        const used = new Array(n + 1).fill(false);
        const current = [];
        const solutions = [];

        const snapshot = (title, message, line = 0, action = "scan") => {
            steps.push({
                kind: "backtracking",
                algo: "permutari",
                title,
                message,
                current: [...current],
                solutions: solutions.map(item => [...item]),
                line,
                action
            });
        };

        const back = k => {
            if (k > n) {
                solutions.push([...current]);
                snapshot("Soluție finală", `Permutare găsită: ${current.join(" ")}`, 3, "solution");
                return;
            }

            for (let v = 1; v <= n; v++) {
                if (used[v]) {
                    snapshot("Pruning", `Valoarea ${v} este deja folosită, o sărim`, 5, "prune");
                    continue;
                }

                current.push(v);
                used[v] = true;
                snapshot("Pas înainte", `Punem ${v} pe poziția ${k}`, 7, "choose");

                back(k + 1);

                used[v] = false;
                current.pop();
                snapshot("Pas înapoi", `Revenim după explorarea lui ${v}`, 9, "backtrack");
            }
        };

        snapshot("Pornire", `Generăm permutările mulțimii {1..${n}}`, 1, "init");
        back(1);
        snapshot("Final", `Total soluții: ${solutions.length}`, 0, "done");
        return steps;
    }

    stepBack() {
        if (!this.steps.length) return;
        if (this.stepIndex > 0) {
            this.stepIndex--;
            this.render();
        }
    }

    stepForward() {
        if (!this.steps.length) return;
        if (this.stepIndex < this.steps.length - 1) {
            this.stepIndex++;
            this.render();
        } else {
            this.stop();
        }
    }

    togglePlay() {
        if (this.running) {
            this.stop();
            return;
        }
        this.running = true;
        this.btnPlay.textContent = "Pauză";
        const run = () => {
            if (!this.running) return;
            this.stepForward();
            if (this.stepIndex >= this.steps.length - 1) {
                this.stop();
                return;
            }
            this.timer = setTimeout(run, Number(this.speedSelect.value || 380));
        };
        run();
    }

    stop() {
        this.running = false;
        this.btnPlay.textContent = "Rulează";
        if (this.timer) {
            clearTimeout(this.timer);
            this.timer = null;
        }
    }

    getCurrentAlgorithm(step = null) {
        return (step && step.algo) || this.algorithmSelect.value || "bubble";
    }

    getPseudocode(algo) {
        const code = {
            bubble: [
                "pentru i = 0 .. n - 2",
                "  pentru j = 0 .. n - i - 2",
                "    compară v[j] cu v[j + 1]",
                "    dacă v[j] > v[j + 1]",
                "      interschimbă v[j] cu v[j + 1]"
            ],
            selection: [
                "pentru i = 0 .. n - 1",
                "  min = i",
                "  pentru j = i + 1 .. n - 1",
                "    compară v[j] cu v[min]",
                "    dacă v[j] < v[min], min = j",
                "  dacă min != i",
                "    interschimbă v[i] cu v[min]"
            ],
            insertion: [
                "pentru i = 1 .. n - 1",
                "  key = v[i]",
                "  j = i - 1",
                "  cât timp j >= 0 și v[j] > key",
                "    mută v[j] la dreapta",
                "    j--",
                "  pune key pe poziția j + 1"
            ],
            quick: [
                "quickSort(stânga, dreapta)",
                "  pivot = v[dreapta]",
                "  p = stânga",
                "  pentru i = stânga .. dreapta - 1",
                "    compară v[i] cu pivotul",
                "    dacă v[i] < pivot, mută v[i] la stânga",
                "    p++",
                "  pune pivotul pe poziția p",
                "  sortează recursiv stânga și dreapta"
            ],
            merge: [
                "mergeSort(stânga, dreapta)",
                "  împarte vectorul în două jumătăți",
                "  sortează recursiv jumătatea stângă",
                "  sortează recursiv jumătatea dreaptă",
                "  compară capetele celor două jumătăți",
                "  copiază elementul mai mic",
                "  copiază restul din stânga",
                "  copiază restul din dreapta"
            ],
            counting: [
                "inițializează vectorul de frecvență",
                "pentru fiecare element x din v",
                "  frecvență[x]++",
                "pentru fiecare valoare posibilă",
                "  cât timp frecvență[valoare] > 0",
                "    pune valoarea în vectorul rezultat"
            ],
            factorial: [
                "fact(n)",
                "  dacă n == 0",
                "    return 1",
                "  calculează fact(n - 1)",
                "  return n * fact(n - 1)"
            ],
            fibonacci: [
                "fib(n)",
                "  dacă n <= 1",
                "    return n",
                "  a = fib(n - 1)",
                "  b = fib(n - 2)",
                "  return a + b"
            ],
            permutari: [
                "back(k)",
                "  dacă k > n",
                "    afișează soluția",
                "  pentru fiecare valoare v",
                "    dacă v este folosită",
                "      continuă",
                "    adaugă v în soluție",
                "    back(k + 1)",
                "    șterge v din soluție",
                "    revino și încearcă altă valoare"
            ]
        };

        return code[algo] || code.bubble;
    }

    renderPseudocode(step) {
        const algo = this.getCurrentAlgorithm(step);
        const activeLine = Number(step.line || 0);
        const lines = this.getPseudocode(algo);
        const htmlLines = lines.map((text, index) => {
            const lineNo = index + 1;
            const isActive = lineNo === activeLine ? " is-active" : "";
            return `
                <div class="viz-code-line${isActive}">
                    <span class="viz-code-line__no">${lineNo}</span>
                    <code>${this.escapeHTML(text)}</code>
                </div>
            `;
        }).join("");

        this.pseudocodePanel.innerHTML = `
            <h3>Pseudocod sincronizat</h3>
            <div class="viz-code-block">${htmlLines}</div>
        `;
    }

    renderExplanation(step) {
        const action = this.getActionLabel(step.action);
        this.explain.innerHTML = `
            <span class="viz-current-explain__label">Ce se întâmplă acum?</span>
            <strong>${this.escapeHTML(action)}</strong>
            <p>${this.escapeHTML(step.message || "Urmărește pasul curent în animație.")}</p>
        `;
    }

    getGuessOptions(correctAction, stepKind) {
        const pools = {
            sorting: ["compare", "swap", "scan", "insert", "pivot", "partition", "split", "merge", "count", "place", "done"],
            stack: ["call", "base", "returns", "done"],
            backtracking: ["choose", "prune", "solution", "backtrack", "done"]
        };
        const pool = pools[stepKind] || pools.sorting;
        const options = [correctAction];
        for (const action of pool) {
            if (options.length >= 3) break;
            if (action !== correctAction) options.push(action);
        }

        return options
            .map((action, index) => ({ action, sort: (action.charCodeAt(0) + index * 17) % 7 }))
            .sort((a, b) => a.sort - b.sort)
            .map(item => item.action);
    }

    renderGuess() {
        const next = this.steps[this.stepIndex + 1];
        if (!next) {
            this.quizPanel.innerHTML = `
                <h3>Ghicește următorul pas</h3>
                <p class="viz-guess__muted">Algoritmul a ajuns la final. Generează un scenariu nou sau apasă Reset.</p>
            `;
            return;
        }

        const options = this.getGuessOptions(next.action, next.kind);
        const buttons = options.map(action => `
            <button type="button" class="btn btn--quiet btn--sm" data-guess-action="${this.escapeHTML(action)}">
                ${this.escapeHTML(this.getActionLabel(action))}
            </button>
        `).join("");

        this.quizPanel.innerHTML = `
            <h3>Ghicește următorul pas</h3>
            <p class="viz-guess__muted">Înainte să apeși „Pas următor”, alege ce crezi că se va întâmpla.</p>
            <div class="viz-guess__options">${buttons}</div>
            <p class="viz-guess__feedback" data-guess-feedback></p>
        `;
    }

    handleGuess(action) {
        const next = this.steps[this.stepIndex + 1];
        const feedback = this.quizPanel.querySelector("[data-guess-feedback]");
        if (!next || !feedback) return;

        const isCorrect = action === next.action;
        feedback.className = `viz-guess__feedback ${isCorrect ? "is-correct" : "is-wrong"}`;
        feedback.textContent = isCorrect
            ? `Corect: urmează „${this.getActionLabel(next.action)}”.`
            : `Aproape. Urmează „${this.getActionLabel(next.action)}”, pentru că pasul următor este: ${next.message}`;
    }

    updateButtonStates() {
        const atStart = this.stepIndex <= 0;
        const atEnd = this.stepIndex >= this.steps.length - 1;
        this.btnPrev.disabled = atStart;
        this.btnStep.disabled = atEnd;
        this.btnPlay.disabled = atEnd;
    }

    render() {
        if (!this.steps.length) return;
        const step = this.steps[this.stepIndex];

        this.meta.innerHTML = `
            <strong>Pas ${this.stepIndex + 1}/${this.steps.length}</strong>
            <span>${step.title || ""}</span>
            <span>${step.message || ""}</span>
        `;

        this.renderExplanation(step);
        this.renderPseudocode(step);
        this.renderGuess();
        this.updateButtonStates();

        if (step.kind === "sorting") {
            this.renderSortingStep(step);
        } else if (step.kind === "stack") {
            this.renderStackStep(step);
        } else {
            this.renderBacktrackingStep(step);
        }
    }

    renderSortingStep(step) {
        const style = getComputedStyle(document.documentElement);
        const colors = {
            primary: (style.getPropertyValue('--color-primary').trim() || "#6E56CF"),
            warning: (style.getPropertyValue('--color-warning').trim() || "#F59E0B"),
            danger:  (style.getPropertyValue('--color-danger').trim()  || "#EF4444")
        };

        const arr = step.array || [];
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        if (!arr.length) return;

        const maxVal = Math.max(...arr);
        const barW = this.canvas.width / arr.length;

        for (let i = 0; i < arr.length; i++) {
            const h = (arr[i] / Math.max(1, maxVal)) * (this.canvas.height - 20);
            if ((step.highlight || []).includes(i)) this.ctx.fillStyle = colors.danger;
            else if (step.pivot === i) this.ctx.fillStyle = colors.warning;
            else this.ctx.fillStyle = colors.primary;
            this.ctx.fillRect(i * barW, this.canvas.height - h, Math.max(1, barW - 2), h);
        }

        this.panel.innerHTML = `
            <div class='step-log'>
                <div>Algoritm: <strong>${this.escapeHTML(this.getAlgorithmLabel(step.algo))}</strong></div>
                <div>Acțiune: <strong>${this.escapeHTML(this.getActionLabel(step.action))}</strong></div>
                <div>Vector curent: ${this.escapeHTML(arr.join(" "))}</div>
            </div>
        `;
    }

    renderStackStep(step) {
        const style = getComputedStyle(document.documentElement);
        const colors = {
            primary: (style.getPropertyValue('--color-primary').trim() || "#6E56CF"),
            warning: (style.getPropertyValue('--color-warning').trim() || "#F59E0B"),
            fg:      (style.getPropertyValue('--color-fg').trim()      || "#F4F4F5"),
            fgOnPrimary: (style.getPropertyValue('--color-fg-on-primary').trim() || "#ffffff")
        };

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        const frames = step.stack || [];
        const boxW = Math.min(300, this.canvas.width - 40);
        const boxH = 34;
        const startX = 20;
        let y = this.canvas.height - 24;

        this.ctx.font = `14px ${this.getFontFamily()}`;
        this.ctx.fillStyle = colors.fg;
        this.ctx.fillText("STACK", 20, 20);

        for (let i = 0; i < frames.length; i++) {
            y -= boxH + 8;
            this.ctx.fillStyle = i === frames.length - 1 ? colors.warning : colors.primary;
            this.ctx.fillRect(startX, y, boxW, boxH);
            this.ctx.fillStyle = colors.fgOnPrimary;
            this.ctx.fillText(frames[i], startX + 10, y + 22);
        }

        this.panel.innerHTML = `
            <div class='step-log'>
                <div>Acțiune: <strong>${this.escapeHTML(this.getActionLabel(step.action))}</strong></div>
                <div>${this.escapeHTML(step.message || "")}</div>
                <div><strong>${step.output ? this.escapeHTML("Output: " + step.output) : ""}</strong></div>
            </div>
        `;
    }

    renderBacktrackingStep(step) {
        const style = getComputedStyle(document.documentElement);
        const colors = {
            primary: (style.getPropertyValue('--color-primary').trim() || "#6E56CF"),
            success: (style.getPropertyValue('--color-success').trim() || "#10B981"),
            fg:      (style.getPropertyValue('--color-fg').trim()      || "#F4F4F5"),
            fgMuted: (style.getPropertyValue('--color-fg-muted').trim() || "#A1A1AA")
        };

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.ctx.font = `16px ${this.getFontFamily()}`;
        this.ctx.fillStyle = colors.fg;
        this.ctx.fillText("Soluție parțială", 20, 30);
        this.ctx.font = `24px ${this.getFontFamily()}`;
        this.ctx.fillStyle = colors.primary;
        this.ctx.fillText((step.current || []).join(" ") || "-", 20, 70);

        const solutions = step.solutions || [];
        this.ctx.font = `14px ${this.getFontFamily()}`;
        this.ctx.fillStyle = colors.fgMuted;
        this.ctx.fillText(`Soluții găsite: ${solutions.length}`, 20, 100);

        const preview = solutions.slice(-6);
        let y = 130;
        for (let i = 0; i < preview.length; i++) {
            this.ctx.fillStyle = colors.success;
            this.ctx.fillText(preview[i].join(" "), 20, y);
            y += 24;
        }

        this.panel.innerHTML = `
            <div class='step-log'>
                <div>Acțiune: <strong>${this.escapeHTML(this.getActionLabel(step.action))}</strong></div>
                <div>${this.escapeHTML(step.message || "")}</div>
                <div>Prefix curent: <strong>${this.escapeHTML((step.current || []).join(" ") || "-")}</strong></div>
                <div>Total soluții: <strong>${solutions.length}</strong></div>
            </div>
        `;
    }
}

document.addEventListener("DOMContentLoaded", () => {
    if (document.getElementById("sorting-visualizer")) {
        new SortingVisualizer("sorting-visualizer");
    }

    const labContainer = document.getElementById("algorithms-lab");
    if (labContainer) {
        new AlgorithmLab(labContainer);
    }
});

window.visualizerInstance = null;
