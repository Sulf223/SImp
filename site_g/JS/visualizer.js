/**
 * visualizer.js
 * 1) Pastreaza vizualizarea pentru metodele de sortare (pagina metoda)
 * 2) Adauga un laborator unificat (sortari + recursivitate + backtracking)
 */

class SortingVisualizer {
    constructor(containerId) {
        this.container = document.getElementById(containerId);
        if (!this.container) return;

        this.algorithmName = (this.container.getAttribute("data-algorithm") || "bubble").toLowerCase();
        this.canvas = document.createElement("canvas");
        this.canvas.width = Math.max(this.container.clientWidth, 320);
        this.canvas.height = 300;
        this.container.appendChild(this.canvas);
        this.ctx = this.canvas.getContext("2d");

        this.array = [];
        this.size = 30;
        this.delay = 35;
        this.isSorting = false;

        this.initControls();
        this.resetArray();
        window.addEventListener("resize", () => this.onResize());
    }

    onResize() {
        this.canvas.width = Math.max(this.container.clientWidth, 320);
        this.draw();
    }

    initControls() {
        const controls = document.createElement("div");
        controls.className = "visualizer-controls";

        const btnStart = document.createElement("button");
        btnStart.textContent = "Start vizualizare";
        btnStart.className = "btn btn-primary";
        btnStart.onclick = () => this.runSort();

        const btnReset = document.createElement("button");
        btnReset.textContent = "Genereaza sir nou";
        btnReset.className = "btn btn-ghost";
        btnReset.onclick = () => this.resetArray();

        controls.appendChild(btnStart);
        controls.appendChild(btnReset);
        this.container.appendChild(controls);
    }

    resetArray() {
        if (this.isSorting) return;
        this.array = [];
        for (let i = 0; i < this.size; i++) {
            this.array.push(Math.floor(Math.random() * 90) + 10);
        }
        this.draw();
    }

    draw(highlightIndices = [], pivotIndex = -1, sortedTail = -1) {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        const barWidth = this.canvas.width / this.size;

        for (let i = 0; i < this.size; i++) {
            const value = this.array[i];
            const barHeight = (value / 100) * this.canvas.height;

            if (i >= sortedTail && sortedTail !== -1) {
                this.ctx.fillStyle = "#10b981";
            } else if (pivotIndex === i) {
                this.ctx.fillStyle = "#f59e0b";
            } else if (highlightIndices.includes(i)) {
                this.ctx.fillStyle = "#ef4444";
            } else {
                this.ctx.fillStyle = "#667eea";
            }

            this.ctx.fillRect(i * barWidth, this.canvas.height - barHeight, Math.max(1, barWidth - 2), barHeight);
        }
    }

    async runSort() {
        if (this.isSorting) return;
        this.isSorting = true;

        if (this.algorithmName.includes("bubble")) await this.bubbleSort();
        else if (this.algorithmName.includes("select")) await this.selectionSort();
        else if (this.algorithmName.includes("insert")) await this.insertionSort();
        else if (this.algorithmName.includes("quick")) await this.quickSort(0, this.array.length - 1);
        else if (this.algorithmName.includes("merge") || this.algorithmName.includes("interclasare")) await this.mergeSort(0, this.array.length - 1);
        else if (this.algorithmName.includes("count")) await this.countingSort();
        else await this.bubbleSort();

        this.draw([], -1, 0);
        this.isSorting = false;
    }

    sleep() {
        return new Promise(resolve => setTimeout(resolve, this.delay));
    }

    async bubbleSort() {
        const len = this.array.length;
        for (let i = 0; i < len; i++) {
            for (let j = 0; j < len - i - 1; j++) {
                this.draw([j, j + 1], -1, len - i);
                await this.sleep();
                if (this.array[j] > this.array[j + 1]) {
                    [this.array[j], this.array[j + 1]] = [this.array[j + 1], this.array[j]];
                }
            }
        }
    }

    async selectionSort() {
        const len = this.array.length;
        for (let i = 0; i < len; i++) {
            let min = i;
            for (let j = i + 1; j < len; j++) {
                this.draw([i, j], min);
                await this.sleep();
                if (this.array[j] < this.array[min]) min = j;
            }
            if (min !== i) {
                [this.array[i], this.array[min]] = [this.array[min], this.array[i]];
                this.draw([i, min], min);
                await this.sleep();
            }
        }
    }

    async insertionSort() {
        const len = this.array.length;
        for (let i = 1; i < len; i++) {
            const key = this.array[i];
            let j = i - 1;
            while (j >= 0 && this.array[j] > key) {
                this.draw([j, j + 1]);
                await this.sleep();
                this.array[j + 1] = this.array[j];
                j--;
            }
            this.array[j + 1] = key;
            this.draw([j + 1]);
            await this.sleep();
        }
    }

    async quickSort(start, end) {
        if (start >= end) return;
        const index = await this.partition(start, end);
        await this.quickSort(start, index - 1);
        await this.quickSort(index + 1, end);
    }

    async partition(start, end) {
        const pivotValue = this.array[end];
        let pivotIndex = start;
        for (let i = start; i < end; i++) {
            this.draw([i, end], pivotIndex);
            await this.sleep();
            if (this.array[i] < pivotValue) {
                [this.array[i], this.array[pivotIndex]] = [this.array[pivotIndex], this.array[i]];
                pivotIndex++;
            }
        }
        [this.array[pivotIndex], this.array[end]] = [this.array[end], this.array[pivotIndex]];
        this.draw([pivotIndex, end], pivotIndex);
        await this.sleep();
        return pivotIndex;
    }

    async mergeSort(start, end) {
        if (start >= end) return;
        const mid = Math.floor((start + end) / 2);
        await this.mergeSort(start, mid);
        await this.mergeSort(mid + 1, end);
        await this.merge(start, mid, end);
    }

    async merge(start, mid, end) {
        const left = this.array.slice(start, mid + 1);
        const right = this.array.slice(mid + 1, end + 1);
        let i = 0;
        let j = 0;
        let k = start;

        while (i < left.length && j < right.length) {
            this.draw([k]);
            await this.sleep();
            if (left[i] <= right[j]) {
                this.array[k++] = left[i++];
            } else {
                this.array[k++] = right[j++];
            }
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
        const max = Math.max(...this.array);
        const count = new Array(max + 1).fill(0);
        for (let i = 0; i < this.array.length; i++) {
            count[this.array[i]]++;
            this.draw([i]);
            await this.sleep();
        }
        let idx = 0;
        for (let v = 0; v < count.length; v++) {
            while (count[v] > 0) {
                this.array[idx] = v;
                this.draw([idx]);
                await this.sleep();
                idx++;
                count[v]--;
            }
        }
    }
}

class AlgorithmLab {
    constructor(container) {
        this.container = container;
        this.steps = [];
        this.stepIndex = 0;
        this.timer = null;
        this.running = false;

        this.buildLayout();
        this.generateScenario();
        window.addEventListener("resize", () => this.onResize());
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

        this.btnGenerate = document.createElement("button");
        this.btnGenerate.className = "btn btn-ghost";
        this.btnGenerate.textContent = "Genereaza scenariu";
        this.btnGenerate.onclick = () => this.generateScenario();

        this.btnStep = document.createElement("button");
        this.btnStep.className = "btn";
        this.btnStep.textContent = "Pas urmator";
        this.btnStep.onclick = () => this.stepForward();

        this.btnPlay = document.createElement("button");
        this.btnPlay.className = "btn btn-primary";
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

        this.canvas = document.createElement("canvas");
        this.canvas.height = 320;
        this.ctx = this.canvas.getContext("2d");

        this.meta = document.createElement("div");
        this.meta.className = "viz-meta";

        this.panel = document.createElement("div");
        this.panel.className = "viz-panel";

        this.container.appendChild(this.controls);
        this.container.appendChild(this.meta);
        this.container.appendChild(this.canvas);
        this.container.appendChild(this.panel);

        this.algorithmSelect.addEventListener("change", () => this.generateScenario());
    }

    onResize() {
        this.canvas.width = Math.max(this.container.clientWidth - 40, 320);
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
        const arr = step.array || [];
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        if (!arr.length) return;

        const maxVal = Math.max(...arr);
        const barW = this.canvas.width / arr.length;

        for (let i = 0; i < arr.length; i++) {
            const h = (arr[i] / Math.max(1, maxVal)) * (this.canvas.height - 20);
            if ((step.highlight || []).includes(i)) this.ctx.fillStyle = "#ef4444";
            else if (step.pivot === i) this.ctx.fillStyle = "#f59e0b";
            else this.ctx.fillStyle = "#667eea";
            this.ctx.fillRect(i * barW, this.canvas.height - h, Math.max(1, barW - 2), h);
        }

        this.panel.innerHTML = `<div class='step-log'>Algoritm: ${step.algo}</div>`;
    }

    renderStackStep(step) {
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        const frames = step.stack || [];
        const boxW = Math.min(300, this.canvas.width - 40);
        const boxH = 34;
        const startX = 20;
        let y = this.canvas.height - 24;

        this.ctx.font = "14px Poppins, sans-serif";
        this.ctx.fillStyle = "#1f2937";
        this.ctx.fillText("STACK", 20, 20);

        for (let i = 0; i < frames.length; i++) {
            y -= boxH + 8;
            this.ctx.fillStyle = i === frames.length - 1 ? "#f59e0b" : "#667eea";
            this.ctx.fillRect(startX, y, boxW, boxH);
            this.ctx.fillStyle = "#ffffff";
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
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        this.ctx.font = "16px Poppins, sans-serif";
        this.ctx.fillStyle = "#1f2937";
        this.ctx.fillText("Solutie partiala", 20, 30);
        this.ctx.font = "24px Poppins, sans-serif";
        this.ctx.fillStyle = "#667eea";
        this.ctx.fillText((step.current || []).join(" ") || "-", 20, 70);

        const solutions = step.solutions || [];
        this.ctx.font = "14px Poppins, sans-serif";
        this.ctx.fillStyle = "#111827";
        this.ctx.fillText(`Solutii gasite: ${solutions.length}`, 20, 100);

        const preview = solutions.slice(-6);
        let y = 130;
        for (let i = 0; i < preview.length; i++) {
            this.ctx.fillStyle = "#10b981";
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
