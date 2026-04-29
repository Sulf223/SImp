(function () {
    function createDataset(type, size, maxValue) {
        var arr = new Array(size);
        for (var i = 0; i < size; i++) {
            arr[i] = Math.floor(Math.random() * maxValue) + 1;
        }

        if (type === "sorted") {
            arr.sort(function (a, b) { return a - b; });
        } else if (type === "reversed") {
            arr.sort(function (a, b) { return b - a; });
        }

        return arr;
    }

    function bubbleSort(input) {
        var arr = input.slice();
        for (var i = 0; i < arr.length; i++) {
            for (var j = 0; j < arr.length - i - 1; j++) {
                if (arr[j] > arr[j + 1]) {
                    var temp = arr[j];
                    arr[j] = arr[j + 1];
                    arr[j + 1] = temp;
                }
            }
        }
        return arr;
    }

    function selectionSort(input) {
        var arr = input.slice();
        for (var i = 0; i < arr.length; i++) {
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
            if (left >= right) {
                return;
            }

            var pivot = arr[right];
            var p = left;

            for (var i = left; i < right; i++) {
                if (arr[i] < pivot) {
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
            var L = arr.slice(left, mid + 1);
            var R = arr.slice(mid + 1, right + 1);
            var i = 0;
            var j = 0;
            var k = left;

            while (i < L.length && j < R.length) {
                if (L[i] <= R[j]) {
                    arr[k++] = L[i++];
                } else {
                    arr[k++] = R[j++];
                }
            }

            while (i < L.length) {
                arr[k++] = L[i++];
            }

            while (j < R.length) {
                arr[k++] = R[j++];
            }
        }

        function sort(left, right) {
            if (left >= right) {
                return;
            }
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

    function benchmark(fn, data) {
        var start = performance.now();
        fn(data);
        return performance.now() - start;
    }

    function colorByIndex(index) {
        var palette = ["#2563eb", "#16a34a", "#f59e0b", "#ef4444", "#7c3aed", "#0ea5e9"];
        return palette[index % palette.length];
    }

    function fontStack(px) {
        var sans = getComputedStyle(document.documentElement).getPropertyValue('--font-sans').trim() || 'Inter, sans-serif';
        return px + "px " + sans;
    }

    function drawChart(canvas, data) {
        var style = getComputedStyle(document.documentElement);
        var colors = {
            fg: (style.getPropertyValue('--color-fg').trim() || "#F4F4F5"),
            border: (style.getPropertyValue('--color-border').trim() || "#27272A")
        };

        var ctx = canvas.getContext("2d");
        var width = canvas.width;
        var height = canvas.height;
        ctx.clearRect(0, 0, width, height);

        if (!data.length) {
            return;
        }

        var max = 0;
        for (var i = 0; i < data.length; i++) {
            if (data[i].time > max) {
                max = data[i].time;
            }
        }
        if (max === 0) {
            max = 1;
        }

        var pad = 42;
        var usableW = width - pad * 2;
        var usableH = height - pad * 2;
        var barW = usableW / data.length;

        ctx.strokeStyle = colors.border;
        ctx.lineWidth = 1;
        ctx.beginPath();
        ctx.moveTo(pad, pad);
        ctx.lineTo(pad, height - pad);
        ctx.lineTo(width - pad, height - pad);
        ctx.stroke();

        for (var j = 0; j < data.length; j++) {
            var x = pad + j * barW + 8;
            var ratio = data[j].time / max;
            var barH = Math.max(2, ratio * (usableH - 10));
            var y = height - pad - barH;

            ctx.fillStyle = data[j].color;
            ctx.fillRect(x, y, Math.max(10, barW - 16), barH);

            ctx.fillStyle = colors.fg;
            ctx.font = fontStack(12);
            ctx.textAlign = "center";
            ctx.fillText(data[j].name, x + Math.max(10, barW - 16) / 2, height - pad + 16);
            ctx.fillText(data[j].time.toFixed(2) + " ms", x + Math.max(10, barW - 16) / 2, y - 6);
        }
    }

    function renderTable(results, tableBody) {
        var html = "";

        if (!results.length) {
            tableBody.innerHTML = '<tr><td colspan="3">Nu exista rezultate.</td></tr>';
            return;
        }

        for (var i = 0; i < results.length; i++) {
            html += "<tr>" +
                "<td>" + results[i].name + "</td>" +
                "<td>" + results[i].complexity + "</td>" +
                "<td>" + results[i].time.toFixed(3) + "</td>" +
                "</tr>";
        }

        tableBody.innerHTML = html;
    }

    function renderLegend(results, legendContainer) {
        var html = "";
        for (var i = 0; i < results.length; i++) {
            html += '<span style="border-left: 10px solid ' + results[i].color + ';">' + results[i].name + "</span>";
        }
        legendContainer.innerHTML = html;
    }

    function run() {
        var button = document.getElementById("run-benchmark");
        var datasetType = document.getElementById("dataset-type");
        var datasetSize = document.getElementById("dataset-size");
        var datasetMax = document.getElementById("dataset-max");
        var canvas = document.getElementById("benchmark-chart");
        var legend = document.getElementById("benchmark-legend");
        var tableBody = document.querySelector("#benchmark-table tbody");

        if (!button || !datasetType || !datasetSize || !datasetMax || !canvas || !legend || !tableBody) {
            return;
        }

        var definitions = [
            { name: "Bubble", fn: bubbleSort, complexity: "O(n^2)" },
            { name: "Selection", fn: selectionSort, complexity: "O(n^2)" },
            { name: "Insertion", fn: insertionSort, complexity: "O(n^2)" },
            { name: "Quick", fn: quickSort, complexity: "O(n log n) avg" },
            { name: "Merge", fn: mergeSort, complexity: "O(n log n)" },
            { name: "Counting", fn: countingSort, complexity: "O(n + k)" }
        ];

        button.addEventListener("click", function () {
            var size = Math.max(20, Math.min(3000, parseInt(datasetSize.value, 10) || 300));
            var maxValue = Math.max(50, Math.min(100000, parseInt(datasetMax.value, 10) || 1000));
            var data = createDataset(datasetType.value, size, maxValue);

            button.disabled = true;
            button.textContent = "Ruleaza...";

            setTimeout(function () {
                var results = [];

                for (var i = 0; i < definitions.length; i++) {
                    var elapsed = benchmark(definitions[i].fn, data);
                    results.push({
                        name: definitions[i].name,
                        complexity: definitions[i].complexity,
                        time: elapsed,
                        color: colorByIndex(i)
                    });
                }

                results.sort(function (a, b) { return a.time - b.time; });
                
                var placeholder = document.getElementById("benchmark-placeholder");
                if (placeholder) placeholder.style.display = "none";
                canvas.style.display = "block";

                renderTable(results, tableBody);
                renderLegend(results, legend);
                drawChart(canvas, results);

                button.disabled = false;
                button.textContent = "Ruleaza comparatia";
            }, 20);
        });
    }

    document.addEventListener("DOMContentLoaded", run);
})();
