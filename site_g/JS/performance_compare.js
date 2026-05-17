(function () {
    var definitions = [
        {
            slug: "bubble",
            name: "Bubble Sort",
            fn: bubbleSort,
            complexity: "O(n^2)",
            bestFor: "explicații de bază, vectori foarte mici",
            strength: "Foarte ușor de urmărit pas cu pas",
            warning: "Devine lent imediat ce n crește",
            tags: ["Ușor", "Stabil", "Didactic"],
            scores: { speed: 1, memory: 3, stable: 3, ease: 3, worst: 1 }
        },
        {
            slug: "selection",
            name: "Selection Sort",
            fn: selectionSort,
            complexity: "O(n^2)",
            bestFor: "puține interschimbări, memorie O(1)",
            strength: "Face maximum n - 1 schimbări",
            warning: "Compară mult chiar și când vectorul e aproape sortat",
            tags: ["In-place", "Puține swap-uri"],
            scores: { speed: 1, memory: 3, stable: 1, ease: 3, worst: 1 }
        },
        {
            slug: "insertion",
            name: "Insertion Sort",
            fn: insertionSort,
            complexity: "O(n^2), O(n) pe aproape sortat",
            bestFor: "vectori mici sau aproape sortați",
            strength: "Excelent când sunt puține inversiuni",
            warning: "Pe date inversate face multe mutări",
            tags: ["Stabil", "Bun pe aproape sortat", "Simplu"],
            scores: { speed: 2, memory: 3, stable: 3, ease: 3, worst: 1 }
        },
        {
            slug: "quick",
            name: "Quick Sort",
            fn: quickSort,
            complexity: "O(n log n) mediu, O(n^2) rău",
            bestFor: "date mari, amestecate, performanță practică",
            strength: "Foarte rapid în medie și folosește puțină memorie",
            warning: "Pivotul prost poate produce caz rău",
            tags: ["Rapid", "In-place", "Practic"],
            scores: { speed: 3, memory: 2, stable: 1, ease: 2, worst: 1 }
        },
        {
            slug: "merge",
            name: "Merge Sort",
            fn: mergeSort,
            complexity: "O(n log n)",
            bestFor: "performanță predictibilă și stabilitate",
            strength: "Are O(n log n) în orice caz",
            warning: "Necesită memorie auxiliară O(n)",
            tags: ["Stabil", "Predictibil", "Sigur"],
            scores: { speed: 3, memory: 1, stable: 3, ease: 2, worst: 3 }
        },
        {
            slug: "counting",
            name: "Counting Sort",
            fn: countingSort,
            complexity: "O(n + k)",
            bestFor: "numere întregi într-un interval mic",
            strength: "Nu compară elemente, doar numără frecvențe",
            warning: "Devine ineficient dacă intervalul k este foarte mare",
            tags: ["Liniar", "Interval mic", "Frecvențe"],
            scores: { speed: 3, memory: 1, stable: 2, ease: 2, worst: 3 }
        }
    ];

    var scenarios = {
        "random-large": {
            title: "Date mari, amestecate",
            recommended: "quick",
            reason: "Quick Sort este de obicei cel mai rapid în practică pe date amestecate, cu timp mediu O(n log n).",
            warning: "Dacă pivotul este ales prost repetat, poate coborî la O(n^2).",
            tags: ["rapid în practică", "puțină memorie", "bun general"],
            dataset: { type: "random", size: 600, max: 100000 }
        },
        small: {
            title: "Vector mic",
            recommended: "insertion",
            reason: "Insertion Sort are implementare scurtă, overhead mic și este ușor de verificat pe exemple mici.",
            warning: "Pentru vectori mari amestecați nu mai este eficient.",
            tags: ["simplu", "stabil", "ușor de explicat"],
            dataset: { type: "random", size: 80, max: 500 }
        },
        "nearly-sorted": {
            title: "Aproape sortat",
            recommended: "insertion",
            reason: "Insertion Sort mută doar elementele ieșite din loc și poate ajunge aproape de O(n).",
            warning: "Dacă datele sunt inversate, avantajul dispare.",
            tags: ["aproape O(n)", "stabil", "puține mutări"],
            dataset: { type: "nearly-sorted", size: 300, max: 1000 }
        },
        "few-unique": {
            title: "Multe valori egale",
            recommended: "counting",
            reason: "Counting Sort profită de repetiții și reconstruiește vectorul din frecvențe.",
            warning: "Funcționează bine doar când valorile pot fi numărate într-un interval rezonabil.",
            tags: ["frecvențe", "fără comparații", "bun pe repetiții"],
            dataset: { type: "few-unique", size: 400, max: 30 }
        },
        "small-range": {
            title: "Interval mic de numere",
            recommended: "counting",
            reason: "Când k este mic, O(n + k) poate bate sortările bazate pe comparații.",
            warning: "Dacă valoarea maximă crește mult, vectorul de frecvențe consumă memorie.",
            tags: ["O(n + k)", "numere întregi", "foarte rapid"],
            dataset: { type: "random", size: 500, max: 60 }
        },
        stable: {
            title: "Vreau stabilitate",
            recommended: "merge",
            reason: "Merge Sort este stabil și păstrează O(n log n) indiferent de forma datelor.",
            warning: "Folosește un vector auxiliar, deci consumă mai multă memorie.",
            tags: ["stabil", "predictibil", "O(n log n)"],
            dataset: { type: "random", size: 300, max: 1000 }
        },
        "low-memory": {
            title: "Memorie puțină",
            recommended: "quick",
            reason: "Quick Sort lucrează în mare parte în același vector și are memorie auxiliară mică în medie.",
            warning: "Dacă ai nevoie de O(1) strict, Selection Sort folosește și mai puțină memorie, dar este mult mai lent.",
            tags: ["memorie mică", "rapid", "in-place"],
            dataset: { type: "random", size: 600, max: 100000 }
        },
        guaranteed: {
            title: "Caz rău sigur",
            recommended: "merge",
            reason: "Merge Sort nu se prăbușește în caz rău: rămâne O(n log n).",
            warning: "Plătești predictibilitatea cu memorie suplimentară O(n).",
            tags: ["caz rău bun", "stabil", "sigur"],
            dataset: { type: "reversed", size: 260, max: 1000 }
        }
    };

    var activeScenario = "random-large";
    var lastResults = null;

    function createDataset(type, size, maxValue) {
        var arr = new Array(size);
        var limit = type === "few-unique" ? Math.min(maxValue, 30) : maxValue;

        for (var i = 0; i < size; i++) {
            arr[i] = Math.floor(Math.random() * limit) + 1;
        }

        if (type === "sorted" || type === "nearly-sorted") {
            arr.sort(function (a, b) { return a - b; });
        } else if (type === "reversed") {
            arr.sort(function (a, b) { return b - a; });
        }

        if (type === "nearly-sorted") {
            var swaps = Math.max(1, Math.floor(size * 0.04));
            for (var s = 0; s < swaps; s++) {
                var a = Math.floor(Math.random() * size);
                var b = Math.floor(Math.random() * size);
                var temp = arr[a];
                arr[a] = arr[b];
                arr[b] = temp;
            }
        }

        return arr;
    }

    function bubbleSort(input) {
        var arr = input.slice();
        var changed = true;
        for (var i = 0; i < arr.length - 1 && changed; i++) {
            changed = false;
            for (var j = 0; j < arr.length - i - 1; j++) {
                if (arr[j] > arr[j + 1]) {
                    var temp = arr[j];
                    arr[j] = arr[j + 1];
                    arr[j + 1] = temp;
                    changed = true;
                }
            }
        }
        return arr;
    }

    function selectionSort(input) {
        var arr = input.slice();
        for (var i = 0; i < arr.length - 1; i++) {
            var min = i;
            for (var j = i + 1; j < arr.length; j++) {
                if (arr[j] < arr[min]) {
                    min = j;
                }
            }
            if (min !== i) {
                var temp = arr[i];
                arr[i] = arr[min];
                arr[min] = temp;
            }
        }
        return arr;
    }

    function insertionSort(input) {
        var arr = input.slice();
        for (var i = 1; i < arr.length; i++) {
            var key = arr[i];
            var j = i - 1;
            while (j >= 0 && arr[j] > key) {
                arr[j + 1] = arr[j];
                j--;
            }
            arr[j + 1] = key;
        }
        return arr;
    }

    function quickSort(input) {
        var arr = input.slice();

        function sort(left, right) {
            if (left >= right) return;

            var pivot = arr[right];
            var p = left;

            for (var i = left; i < right; i++) {
                if (arr[i] <= pivot) {
                    var t = arr[i];
                    arr[i] = arr[p];
                    arr[p] = t;
                    p++;
                }
            }

            var tp = arr[p];
            arr[p] = arr[right];
            arr[right] = tp;

            sort(left, p - 1);
            sort(p + 1, right);
        }

        sort(0, arr.length - 1);
        return arr;
    }

    function mergeSort(input) {
        var arr = input.slice();

        function merge(left, mid, right) {
            var leftPart = arr.slice(left, mid + 1);
            var rightPart = arr.slice(mid + 1, right + 1);
            var i = 0;
            var j = 0;
            var k = left;

            while (i < leftPart.length && j < rightPart.length) {
                if (leftPart[i] <= rightPart[j]) {
                    arr[k++] = leftPart[i++];
                } else {
                    arr[k++] = rightPart[j++];
                }
            }

            while (i < leftPart.length) arr[k++] = leftPart[i++];
            while (j < rightPart.length) arr[k++] = rightPart[j++];
        }

        function sort(left, right) {
            if (left >= right) return;
            var mid = Math.floor((left + right) / 2);
            sort(left, mid);
            sort(mid + 1, right);
            merge(left, mid, right);
        }

        sort(0, arr.length - 1);
        return arr;
    }

    function countingSort(input) {
        var arr = input.slice();
        if (!arr.length) return arr;

        var max = Math.max.apply(null, arr);
        var count = new Array(max + 1).fill(0);
        for (var i = 0; i < arr.length; i++) {
            count[arr[i]]++;
        }

        var idx = 0;
        for (var v = 0; v < count.length; v++) {
            while (count[v] > 0) {
                arr[idx++] = v;
                count[v]--;
            }
        }
        return arr;
    }

    function benchmark(definition, data, iterations) {
        var totalTime = 0;
        for (var i = 0; i < iterations; i++) {
            var start = performance.now();
            definition.fn(data);
            totalTime += performance.now() - start;
        }
        return totalTime / iterations;
    }

    function colorByIndex(index) {
        var style = getComputedStyle(document.documentElement);
        var palette = [
            style.getPropertyValue('--color-primary').trim() || "#6E56CF",
            style.getPropertyValue('--color-success').trim() || "#16a34a",
            style.getPropertyValue('--color-warning').trim() || "#f59e0b",
            style.getPropertyValue('--color-danger').trim() || "#ef4444",
            style.getPropertyValue('--color-accent').trim() || "#06b6d4",
            style.getPropertyValue('--color-fg-subtle').trim() || "#94a3b8"
        ];
        return palette[index % palette.length];
    }

    function fontStack(px) {
        var sans = getComputedStyle(document.documentElement).getPropertyValue('--font-sans').trim() || 'Inter, sans-serif';
        return px + "px " + sans;
    }

    function drawChart(canvas, data) {
        var style = getComputedStyle(document.documentElement);
        var colors = {
            fg: style.getPropertyValue('--color-fg').trim() || "#F4F4F5",
            muted: style.getPropertyValue('--color-fg-muted').trim() || "#A1A1AA",
            border: style.getPropertyValue('--color-border').trim() || "#27272A",
            bg: style.getPropertyValue('--color-bg').trim() || "#09090B"
        };

        var ctx = canvas.getContext("2d");
        var width = canvas.width;
        var height = canvas.height;
        ctx.clearRect(0, 0, width, height);

        if (!data.length) return;

        var max = data.reduce(function (acc, item) {
            return Math.max(acc, item.time || 0);
        }, 0) || 1;

        var pad = 54;
        var usableW = width - pad * 2;
        var usableH = height - pad * 2;
        var barW = usableW / data.length;

        ctx.fillStyle = colors.bg;
        ctx.fillRect(0, 0, width, height);

        ctx.strokeStyle = colors.border;
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(pad, pad);
        ctx.lineTo(pad, height - pad);
        ctx.lineTo(width - pad, height - pad);
        ctx.stroke();

        ctx.fillStyle = colors.muted;
        ctx.font = fontStack(12);
        ctx.textAlign = "left";
        ctx.fillText("mai mic = mai rapid", pad, pad - 18);

        for (var j = 0; j < data.length; j++) {
            var x = pad + j * barW + 10;
            var ratio = data[j].time / max;
            var barH = Math.max(4, ratio * (usableH - 12));
            var y = height - pad - barH;
            var widthBar = Math.max(12, barW - 20);

            ctx.fillStyle = data[j].color;
            ctx.fillRect(x, y, widthBar, barH);

            ctx.fillStyle = colors.fg;
            ctx.font = fontStack(12);
            ctx.textAlign = "center";
            ctx.fillText(data[j].name.replace(" Sort", ""), x + widthBar / 2, height - pad + 18);
            ctx.fillText(formatTime(data[j].time), x + widthBar / 2, Math.max(18, y - 8));
        }
    }

    function escapeHtml(value) {
        return String(value)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function formatTime(value) {
        if (value === null || value === undefined || Number.isNaN(value)) return "-";
        if (value < 0.001) return "<0.001 ms";
        return value.toFixed(value < 1 ? 3 : 2) + " ms";
    }

    function scoreLabel(score) {
        if (score >= 3) return "Bun";
        if (score === 2) return "Ok";
        return "Slab";
    }

    function getDefinition(slug) {
        return definitions.find(function (definition) { return definition.slug === slug; }) || definitions[0];
    }

    function renderTags(tags) {
        return tags.map(function (tag) {
            return '<span class="comparison-tag">' + escapeHtml(tag) + '</span>';
        }).join("");
    }

    function renderRecommendation() {
        var scenario = scenarios[activeScenario] || scenarios["random-large"];
        var definition = getDefinition(scenario.recommended);
        var name = document.getElementById("recommendation-name");
        var reason = document.getElementById("recommendation-reason");
        var warning = document.getElementById("recommendation-warning");
        var tags = document.getElementById("recommendation-tags");

        if (name) name.textContent = definition.name;
        if (reason) reason.textContent = scenario.reason;
        if (warning) warning.textContent = scenario.warning;
        if (tags) tags.innerHTML = renderTags(scenario.tags.concat(definition.tags.slice(0, 2)));
    }

    function renderHeatmap() {
        var heatmap = document.getElementById("comparison-heatmap");
        if (!heatmap) return;

        var scenario = scenarios[activeScenario] || scenarios["random-large"];
        var columns = [
            { key: "speed", label: "Viteză" },
            { key: "memory", label: "Memorie" },
            { key: "stable", label: "Stabilitate" },
            { key: "ease", label: "Ușurință" },
            { key: "worst", label: "Caz rău" }
        ];

        var html = '<div class="heatmap-row heatmap-row--head"><span>Algoritm</span>';
        columns.forEach(function (column) {
            html += '<span>' + escapeHtml(column.label) + '</span>';
        });
        html += '</div>';

        definitions.forEach(function (definition) {
            var recommended = definition.slug === scenario.recommended ? " is-recommended" : "";
            html += '<div class="heatmap-row' + recommended + '">';
            html += '<strong>' + escapeHtml(definition.name) + '</strong>';
            columns.forEach(function (column) {
                var score = definition.scores[column.key];
                html += '<span class="heat-cell heat-cell--' + score + '">' + scoreLabel(score) + '</span>';
            });
            html += '</div>';
        });

        heatmap.innerHTML = html;
    }

    function renderTable(results) {
        var tableBody = document.querySelector("#benchmark-table tbody");
        if (!tableBody) return;

        var scenario = scenarios[activeScenario] || scenarios["random-large"];
        var resultMap = {};
        if (results) {
            results.forEach(function (result, index) {
                resultMap[result.slug] = Object.assign({}, result, { rank: index + 1 });
            });
        }

        var rows = definitions.slice();
        if (results) {
            rows.sort(function (a, b) {
                var aResult = resultMap[a.slug];
                var bResult = resultMap[b.slug];
                if (!aResult && !bResult) return 0;
                if (!aResult) return 1;
                if (!bResult) return -1;
                return aResult.time - bResult.time;
            });
        }

        tableBody.innerHTML = rows.map(function (definition) {
            var result = resultMap[definition.slug];
            var recommended = definition.slug === scenario.recommended ? " is-recommended" : "";
            var timeCell = result
                ? '<strong>' + escapeHtml(formatTime(result.time)) + '</strong><small>#' + result.rank + ' în test</small>'
                : '<span class="muted">rulează benchmark</span>';
            var status = result && result.error
                ? '<span class="comparison-status comparison-status--bad">eroare</span>'
                : result && result.rank === 1
                    ? '<span class="comparison-status comparison-status--good">cel mai rapid</span>'
                    : result
                        ? '<span class="comparison-status">măsurat</span>'
                        : '';

            return '<tr class="' + recommended.trim() + '">' +
                '<td><strong>' + escapeHtml(definition.name) + '</strong>' + status + '</td>' +
                '<td>' + escapeHtml(definition.bestFor) + '</td>' +
                '<td>' + escapeHtml(definition.strength) + '</td>' +
                '<td>' + escapeHtml(definition.warning) + '</td>' +
                '<td><code>' + escapeHtml(definition.complexity) + '</code></td>' +
                '<td>' + timeCell + '</td>' +
                '</tr>';
        }).join("");
    }

    function renderLegend(results, legendContainer) {
        if (!legendContainer) return;
        legendContainer.innerHTML = results.map(function (result) {
            return '<span style="border-left-color: ' + result.color + ';">' + escapeHtml(result.name) + '</span>';
        }).join("");
    }

    function renderConclusion(results, size, datasetType, maxValue) {
        var container = document.getElementById("benchmark-conclusion");
        if (!container) return;

        var validResults = (results || []).filter(function (result) {
            return Number.isFinite(result.time);
        });

        if (!validResults.length) {
            container.innerHTML = '<h2>Nu există rezultate valide</h2><p>Încearcă un număr mai mic de elemente sau un interval mai restrâns.</p>';
            return;
        }

        var scenario = scenarios[activeScenario] || scenarios["random-large"];
        var recommended = getDefinition(scenario.recommended);
        var fastest = validResults[0];
        var recommendedResult = validResults.find(function (result) {
            return result.slug === recommended.slug;
        });
        var matchText = fastest.slug === recommended.slug
            ? "Recomandarea s-a confirmat în testul tău."
            : "În testul tău a câștigat alt algoritm, deci contextul concret contează.";
        var difference = "";

        if (recommendedResult && fastest.slug !== recommended.slug && fastest.time > 0) {
            difference = " " + recommended.name + " a fost de aproximativ " + (recommendedResult.time / fastest.time).toFixed(1) + "x mai lent aici.";
        }

        container.innerHTML =
            '<h2>' + escapeHtml(fastest.name) + ' a fost cel mai rapid</h2>' +
            '<p>' + escapeHtml(matchText + difference) + '</p>' +
            '<dl class="comparison-conclusion-list">' +
                '<div><dt>Dataset</dt><dd>' + escapeHtml(datasetType) + ', ' + escapeHtml(size) + ' elemente</dd></div>' +
                '<div><dt>Interval</dt><dd>1...' + escapeHtml(maxValue) + '</dd></div>' +
                '<div><dt>De reținut</dt><dd>' + escapeHtml(scenario.warning) + '</dd></div>' +
            '</dl>';
    }

    function setFieldValue(id, value) {
        var field = document.getElementById(id);
        if (field) field.value = value;
    }

    function resetBenchmarkOutput() {
        var canvas = document.getElementById("benchmark-chart");
        var placeholder = document.getElementById("benchmark-placeholder");
        var legend = document.getElementById("benchmark-legend");
        var iterationInfo = document.getElementById("iteration-info");
        var liveStatus = document.getElementById("benchmark-live-status");
        var conclusion = document.getElementById("benchmark-conclusion");

        lastResults = null;

        if (canvas) {
            var ctx = canvas.getContext("2d");
            if (ctx) ctx.clearRect(0, 0, canvas.width, canvas.height);
            canvas.hidden = true;
        }
        if (placeholder) placeholder.hidden = false;
        if (legend) legend.innerHTML = "";
        if (iterationInfo) iterationInfo.textContent = "";
        if (liveStatus) {
            liveStatus.hidden = true;
            liveStatus.textContent = "";
        }
        if (conclusion) {
            conclusion.innerHTML = '<h2>Rulează o comparație</h2>' +
                '<p>După benchmark, aici apare pe scurt cine a câștigat, dacă recomandarea se potrivește și ce limită trebuie ținută minte.</p>';
        }
    }

    function applyScenario(slug) {
        activeScenario = scenarios[slug] ? slug : "random-large";
        var scenario = scenarios[activeScenario];

        document.querySelectorAll(".scenario-chip").forEach(function (chip) {
            chip.classList.toggle("is-active", chip.getAttribute("data-scenario") === activeScenario);
        });

        setFieldValue("dataset-type", scenario.dataset.type);
        setFieldValue("dataset-size", scenario.dataset.size);
        setFieldValue("dataset-max", scenario.dataset.max);

        resetBenchmarkOutput();
        renderRecommendation();
        renderHeatmap();
        renderTable(null);
    }

    function getIterations(size, quickRun) {
        if (quickRun) {
            if (size <= 300) return 10;
            if (size <= 800) return 8;
            if (size <= 1500) return 3;
            return 1;
        }
        if (size <= 300) return 40;
        if (size <= 800) return 15;
        if (size <= 1500) return 5;
        return 1;
    }

    function setButtonLoading(buttons, loading) {
        buttons.forEach(function (button) {
            if (!button) return;
            button.disabled = loading;
            if (loading) {
                button.dataset.originalHtml = button.innerHTML;
                button.textContent = "Rulează...";
            } else {
                if (button.dataset.originalHtml) {
                    button.innerHTML = button.dataset.originalHtml;
                }
                delete button.dataset.originalHtml;
            }
        });
    }

    function runBenchmark(quickRun) {
        var datasetType = document.getElementById("dataset-type");
        var datasetSize = document.getElementById("dataset-size");
        var datasetMax = document.getElementById("dataset-max");
        var canvas = document.getElementById("benchmark-chart");
        var legend = document.getElementById("benchmark-legend");
        var placeholder = document.getElementById("benchmark-placeholder");
        var iterationInfo = document.getElementById("iteration-info");
        var liveStatus = document.getElementById("benchmark-live-status");
        var buttons = [
            document.getElementById("run-benchmark"),
            document.getElementById("run-live-benchmark")
        ];

        if (!datasetType || !datasetSize || !datasetMax || !canvas) return;

        var size = Math.max(20, Math.min(3000, parseInt(datasetSize.value, 10) || 300));
        var maxValue = Math.max(20, Math.min(100000, parseInt(datasetMax.value, 10) || 1000));
        var data = createDataset(datasetType.value, size, maxValue);
        var iterations = getIterations(size, quickRun);

        setButtonLoading(buttons, true);
        if (iterationInfo) {
            iterationInfo.textContent = "Se calculează media a " + iterations + " rulări pentru " + size + " elemente.";
        }
        if (liveStatus) {
            liveStatus.hidden = false;
            liveStatus.textContent = "Benchmark în desfășurare...";
        }

        setTimeout(function () {
            var results = [];

            definitions.forEach(function (definition, index) {
                try {
                    var elapsed = benchmark(definition, data, iterations);
                    results.push({
                        slug: definition.slug,
                        name: definition.name,
                        complexity: definition.complexity,
                        time: elapsed,
                        color: colorByIndex(index)
                    });
                } catch (error) {
                    results.push({
                        slug: definition.slug,
                        name: definition.name,
                        complexity: definition.complexity,
                        time: Number.POSITIVE_INFINITY,
                        color: colorByIndex(index),
                        error: error.message
                    });
                }
            });

            results.sort(function (a, b) { return a.time - b.time; });
            lastResults = results;

            if (placeholder) placeholder.hidden = true;
            canvas.hidden = false;

            renderTable(results);
            renderLegend(results.filter(function (result) { return Number.isFinite(result.time); }), legend);
            drawChart(canvas, results.filter(function (result) { return Number.isFinite(result.time); }));
            renderHeatmap();
            renderConclusion(results, size, datasetType.value, maxValue);

            if (iterationInfo) {
                iterationInfo.textContent = "Ultimul test: media a " + iterations + " rulări.";
            }
            if (liveStatus) {
                liveStatus.textContent = "Comparația este gata. Tabelul este ordonat după timpul măsurat.";
            }

            setButtonLoading(buttons, false);
        }, 30);
    }

    function bindControls() {
        document.querySelectorAll(".scenario-chip").forEach(function (button) {
            button.addEventListener("click", function () {
                applyScenario(button.getAttribute("data-scenario"));
            });
        });

        var runButton = document.getElementById("run-benchmark");
        if (runButton) {
            runButton.addEventListener("click", function () { runBenchmark(false); });
        }

        var quickButton = document.getElementById("run-live-benchmark");
        if (quickButton) {
            quickButton.addEventListener("click", function () { runBenchmark(true); });
        }

        ["dataset-type", "dataset-size", "dataset-max"].forEach(function (id) {
            var field = document.getElementById(id);
            if (!field) return;
            field.addEventListener("change", function () {
                resetBenchmarkOutput();
                renderTable(null);
            });
        });
    }

    function init() {
        bindControls();
        applyScenario(activeScenario);
    }

    document.addEventListener("DOMContentLoaded", init);
})();
