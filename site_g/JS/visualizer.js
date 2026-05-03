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
    updateStatsUI() {
        const compEl = document.getElementById('comparisons');
        const swapEl = document.getElementById('swaps');
        if (compEl) compEl.innerText = this.comparisons;
        if (swapEl) swapEl.innerText = this.swaps;
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
        this.updateStats("Contoare resetate.");
    }

    updateStats(message) {
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

    buildLayout() {
        this.controls = document.createElement("div");
        this.controls.className = "visualizer-controls";

        this.algorithmSelect = document.createElement("select");
        this.algorithmSelect.className = "viz-select";
        this.algorithmSelect.innerHTML = [
            "<option value='bubble'>Bubble Sort</option>",
            "<option value='selection'>Selection Sort</option>",
            "<option value='insertion'>Insertion Sort</option>",
            "<option value='quick'>Quick Sort</option>",
            "<option value='merge'>Merge Sort</option>",
            "<option value='counting'>Counting Sort</option>",
            "<option value='factorial'>Recursivitate: Factorial</option>",
            "<option value='fibonacci'>Recursivitate: Fibonacci</option>",
            "<option value='permutari'>Backtracking: Permutari</option>"
        ].join("");

        this.inputN = document.createElement("input");
        this.inputN.type = "number";
        this.inputN.min = "3";
        this.inputN.max = "9";
        this.inputN.value = "6";
        this.inputN.className = "viz-input";
        this.inputN.style.width = "60px";

        this.btnGenerate = document.createElement("button");
        this.btnGenerate.className = "btn btn--ghost btn--sm";
        this.btnGenerate.textContent = "Genereaza scenariu";
        this.btnGenerate.onclick = () => this.generateScenario();

        this.btnStep = document.createElement("button");
        this.btnStep.className = "btn btn--sm";
        this.btnStep.textContent = "Pas urmator";
        this.btnStep.onclick = () => this.stepForward();

        this.btnPlay = document.createElement("button");
        this.btnPlay.className = "btn btn--primary btn--sm";
        this.btnPlay.textContent = "Ruleaza";
        this.btnPlay.onclick = () => this.togglePlay();

        this.speedSelect = document.createElement("select");
        this.speedSelect.className = "viz-select";
        this.speedSelect.innerHTML = "<option value='700'>Viteza: lent</option><option value='380' selected>Viteza: mediu</option><option value='180'>Viteza: rapid</option>";

        this.controls.appendChild(this.algorithmSelect);
        this.controls.appendChild(this.inputN);
        this.controls.appendChild(this.btnGenerate);
        this.controls.appendChild(this.btnStep);
        this.controls.appendChild(this.btnPlay);
        this.controls.appendChild(this.speedSelect);

        this.meta = document.createElement("div");
        this.meta.className = "viz-meta";

        this.canvas = document.createElement("canvas");
        this.canvas.height = 320;
        this.ctx = this.canvas.getContext("2d");

        this.panel = document.createElement("div");
        this.panel.className = "viz-panel";

        this.container.appendChild(this.controls);
        this.container.appendChild(this.meta);
        this.container.appendChild(this.canvas);
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

        if (["bubble", "selection", "insertion", "quick", "merge", "counting"].includes(algo)) {
            const size = Math.max(5, Math.min(24, n * 2));
            const arr = this.makeRandomArray(size);
            this.steps = this.buildSortingSteps(algo, arr);
        } else if (algo === "factorial") {
            this.steps = this.buildFactorialSteps(Math.min(n, 8));
        } else if (algo === "fibonacci") {
            this.steps = this.buildFibonacciSteps(Math.min(n, 8));
        } else {
            this.steps = this.buildPermutationSteps(Math.min(n, 6));
        }

        this.onResize();
        this.render();
    }

    makeRandomArray(size) {
        const arr = [];
        for (let i = 0; i < size; i++) {
            arr.push(Math.floor(Math.random() * 90) + 10);
        }
        return arr;
    }

    buildSortingSteps(algo, source) {
        const arr = [...source];
        const steps = [];
        const push = (message, highlight = [], pivot = -1) => {
            steps.push({
                kind: "sorting",
                algo,
                message,
                array: [...arr],
                highlight,
                pivot
            });
        };

        push("Stare initiala");

        if (algo === "bubble") {
            for (let i = 0; i < arr.length; i++) {
                for (let j = 0; j < arr.length - i - 1; j++) {
                    push(`Comparam ${arr[j]} si ${arr[j + 1]}`, [j, j + 1]);
                    if (arr[j] > arr[j + 1]) {
                        [arr[j], arr[j + 1]] = [arr[j + 1], arr[j]];
                        push("Interschimbare", [j, j + 1]);
                    }
                }
            }
        } else if (algo === "selection") {
            for (let i = 0; i < arr.length; i++) {
                let min = i;
                for (let j = i + 1; j < arr.length; j++) {
                    push(`Cautam minim: i=${i}, j=${j}`, [i, j], min);
                    if (arr[j] < arr[min]) min = j;
                }
                if (min !== i) {
                    [arr[i], arr[min]] = [arr[min], arr[i]];
                    push("Mutam minimul pe pozitia curenta", [i, min], min);
                }
            }
        } else if (algo === "insertion") {
            for (let i = 1; i < arr.length; i++) {
                const key = arr[i];
                let j = i - 1;
                push(`Cheia este ${key}`, [i]);
                while (j >= 0 && arr[j] > key) {
                    arr[j + 1] = arr[j];
                    push(`Mutam ${arr[j]} spre dreapta`, [j, j + 1]);
                    j--;
                }
                arr[j + 1] = key;
                push(`Inseram cheia ${key}`, [j + 1]);
            }
        } else if (algo === "quick") {
            const quick = (lo, hi) => {
                if (lo >= hi) return;
                const pivot = arr[hi];
                let p = lo;
                push(`Pivot ${pivot} pe segment [${lo}, ${hi}]`, [hi], hi);
                for (let i = lo; i < hi; i++) {
                    push(`Comparam ${arr[i]} cu pivot ${pivot}`, [i, hi], p);
                    if (arr[i] < pivot) {
                        [arr[i], arr[p]] = [arr[p], arr[i]];
                        push("Mutam element in stanga pivotului", [i, p], p);
                        p++;
                    }
                }
                [arr[p], arr[hi]] = [arr[hi], arr[p]];
                push("Fixam pivotul pe pozitia finala", [p, hi], p);
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
                    push(`Interclasare pe pozitia ${k - 1}`, [k - 1]);
                }
                while (i < left.length) {
                    arr[k++] = left[i++];
                    push(`Copiem rest stanga pe ${k - 1}`, [k - 1]);
                }
                while (j < right.length) {
                    arr[k++] = right[j++];
                    push(`Copiem rest dreapta pe ${k - 1}`, [k - 1]);
                }
            };
            const rec = (lo, hi) => {
                if (lo >= hi) return;
                const mid = Math.floor((lo + hi) / 2);
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
                push(`Frecventa pentru ${arr[i]} creste`, [i]);
            }
            let pos = 0;
            for (let value = 0; value < freq.length; value++) {
                while (freq[value] > 0) {
                    arr[pos] = value;
                    push(`Plasam ${value} pe pozitia ${pos}`, [pos]);
                    pos++;
                    freq[value]--;
                }
            }
        }

        push("Sortare finalizata");
        return steps;
    }

    buildFactorialSteps(n) {
        const steps = [];
        const stack = [];

        const rec = x => {
            stack.push(`fact(${x})`);
            steps.push({
                kind: "stack",
                title: `Apel fact(${x})`,
                message: `Intram in apelul fact(${x})`,
                stack: [...stack],
                output: null
            });

            if (x === 0) {
                steps.push({
                    kind: "stack",
                    title: "Caz de baza",
                    message: "n == 0, returnam 1",
                    stack: [...stack],
                    output: "return 1"
                });
                stack.pop();
                return 1;
            }

            const result = x * rec(x - 1);
            steps.push({
                kind: "stack",
                title: `Intoarcere din fact(${x})`,
                message: `Calculam ${x} * fact(${x - 1}) = ${result}`,
                stack: [...stack],
                output: `return ${result}`
            });
            stack.pop();
            return result;
        };

        const finalValue = rec(n);
        steps.push({
            kind: "stack",
            title: "Rezultat final",
            message: `factorial(${n}) = ${finalValue}`,
            stack: [],
            output: String(finalValue)
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
                title: `Apel fib(${x})`,
                message: `Intram in fib(${x})`,
                stack: [...stack],
                output: null
            });

            if (x <= 1) {
                steps.push({
                    kind: "stack",
                    title: "Caz de baza",
                    message: `fib(${x}) = ${x}`,
                    stack: [...stack],
                    output: `return ${x}`
                });
                stack.pop();
                return x;
            }

            const a = rec(x - 1);
            const b = rec(x - 2);
            const sum = a + b;

            steps.push({
                kind: "stack",
                title: `Combinam rezultate`,
                message: `fib(${x - 1}) + fib(${x - 2}) = ${a} + ${b} = ${sum}`,
                stack: [...stack],
                output: `return ${sum}`
            });
            stack.pop();
            return sum;
        };

        const finalValue = rec(n);
        steps.push({
            kind: "stack",
            title: "Rezultat final",
            message: `fib(${n}) = ${finalValue}`,
            stack: [],
            output: String(finalValue)
        });

        return steps;
    }

    buildPermutationSteps(n) {
        const steps = [];
        const used = new Array(n + 1).fill(false);
        const current = [];
        const solutions = [];

        const snapshot = (title, message) => {
            steps.push({
                kind: "backtracking",
                title,
                message,
                current: [...current],
                solutions: solutions.map(item => [...item])
            });
        };

        const back = k => {
            if (k > n) {
                solutions.push([...current]);
                snapshot("Solutie finala", `Permutare gasita: ${current.join(" ")}`);
                return;
            }

            for (let v = 1; v <= n; v++) {
                if (used[v]) {
                    snapshot("Pruning", `Valoarea ${v} este deja folosita, o sarim`);
                    continue;
                }

                current.push(v);
                used[v] = true;
                snapshot("Pas inainte", `Punem ${v} pe pozitia ${k}`);

                back(k + 1);

                used[v] = false;
                current.pop();
                snapshot("Pas inapoi", `Revenim dupa explorarea lui ${v}`);
            }
        };

        snapshot("Pornire", `Generam permutarile multimii {1..${n}}`);
        back(1);
        snapshot("Final", `Total solutii: ${solutions.length}`);
        return steps;
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
        this.btnPlay.textContent = "Pauza";
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
        this.btnPlay.textContent = "Ruleaza";
        if (this.timer) {
            clearTimeout(this.timer);
            this.timer = null;
        }
    }

    render() {
        if (!this.steps.length) return;
        const step = this.steps[this.stepIndex];

        this.meta.innerHTML = `
            <strong>Pas ${this.stepIndex + 1}/${this.steps.length}</strong>
            <span>${step.title || ""}</span>
            <span>${step.message || ""}</span>
        `;

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

        this.panel.innerHTML = `<div class='step-log'>Algoritm: ${step.algo}</div>`;
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
                <div>${step.message || ""}</div>
                <div><strong>${step.output ? "Output: " + step.output : ""}</strong></div>
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
        this.ctx.fillText("Solutie partiala", 20, 30);
        this.ctx.font = `24px ${this.getFontFamily()}`;
        this.ctx.fillStyle = colors.primary;
        this.ctx.fillText((step.current || []).join(" ") || "-", 20, 70);

        const solutions = step.solutions || [];
        this.ctx.font = `14px ${this.getFontFamily()}`;
        this.ctx.fillStyle = colors.fgMuted;
        this.ctx.fillText(`Solutii gasite: ${solutions.length}`, 20, 100);

        const preview = solutions.slice(-6);
        let y = 130;
        for (let i = 0; i < preview.length; i++) {
            this.ctx.fillStyle = colors.success;
            this.ctx.fillText(preview[i].join(" "), 20, y);
            y += 24;
        }

        this.panel.innerHTML = `
            <div class='step-log'>
                <div>${step.message || ""}</div>
                <div>Prefix curent: <strong>${(step.current || []).join(" ") || "-"}</strong></div>
                <div>Total solutii: <strong>${solutions.length}</strong></div>
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