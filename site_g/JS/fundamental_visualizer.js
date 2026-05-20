(function () {
    function buildSteps(topic) {
        if (topic === "recursivitate") {
            return [
                "Apel principal: fact(4)",
                "fact(4) -> fact(3)",
                "fact(3) -> fact(2)",
                "fact(2) -> fact(1)",
                "fact(1) -> fact(0) (caz de bază)",
                "Return: fact(0)=1",
                "Return: fact(1)=1",
                "Return: fact(2)=2",
                "Return: fact(3)=6",
                "Return final: fact(4)=24"
            ];
        }

        if (topic === "backtracking") {
            return [
                "Pornire: x = [_, _, _]",
                "x[1] = 1 (pas înainte)",
                "x[2] = 1 (invalid, deja folosit)",
                "x[2] = 2 (valid)",
                "x[3] = 3 -> soluție: 1 2 3",
                "Pas înapoi la x[3] și x[2]",
                "x[2] = 3, x[3] = 2 -> soluție: 1 3 2",
                "Pas înapoi la x[1], alegem 2",
                "Generăm următoarele soluții...",
                "Final: toate permutările generate"
            ];
        }

        if (topic === "greedy") {
            return [
                "Problema: suma = 87, monede = {50, 10, 5, 1}",
                "Alegem 50 (cea mai mare monedă posibilă)",
                "Ramas: 37, alegem 10",
                "Ramas: 27, alegem 10",
                "Ramas: 17, alegem 10",
                "Ramas: 7, alegem 5",
                "Ramas: 2, alegem 1",
                "Ramas: 1, alegem 1",
                "Ramas: 0, stop",
                "Rezultat: 50 + 10 + 10 + 10 + 5 + 1 + 1"
            ];
        }

        return [
            "Problema: căutăm 23 în vector sortat",
            "Interval inițial: [0, n-1]",
            "Calculăm mijlocul și comparăm",
            "Dacă 23 e mai mare, păstrăm jumătatea dreaptă",
            "Recalculăm mijlocul în intervalul nou",
            "Dacă 23 e mai mic, păstrăm jumătatea stângă",
            "Continuăm până găsim valoarea",
            "Sau până intervalul devine vid",
            "Număr de pași ~ log2(n)",
            "Concluzie: mult mai rapid decât căutarea liniară"
        ];
    }

    function render(container, steps, index) {
        var safeIndex = Math.max(0, Math.min(index, steps.length - 1));
        var current = steps[safeIndex];

        var html = "";
        html += '<div class="visualizer-controls">';
        html += '<button class="btn btn-primary" data-action="prev">Pas anterior</button>';
        html += '<button class="btn btn-ghost" data-action="next">Pas următor</button>';
        html += '<button class="btn" data-action="reset">Reset</button>';
        html += '</div>';

        html += '<div class="viz-panel">';
        html += '<h3>Pas ' + (safeIndex + 1) + ' / ' + steps.length + '</h3>';
        html += '<p>' + current + '</p>';
        html += '</div>';

        html += '<div class="table-wrapper" style="margin-top:12px;">';
        html += '<table><thead><tr><th>Istoric pași</th></tr></thead><tbody>';
        for (var i = 0; i <= safeIndex; i++) {
            html += '<tr><td>' + steps[i] + '</td></tr>';
        }
        html += '</tbody></table></div>';

        container.innerHTML = html;
    }

    document.addEventListener("DOMContentLoaded", function () {
        var container = document.getElementById("fundamental-visualizer");
        if (!container) return;

        var topic = container.getAttribute("data-topic") || "recursivitate";
        var steps = buildSteps(topic);
        var index = 0;

        // FIX [A4]: Atașăm listener-ul o singură dată pe container (event delegation)
        container.addEventListener("click", function (e) {
            var btn = e.target.closest('[data-action]');
            if (!btn) return;
            
            var action = btn.getAttribute("data-action");
            if (action === "prev") {
                index = Math.max(0, index - 1);
                render(container, steps, index);
            } else if (action === "next") {
                index = Math.min(steps.length - 1, index + 1);
                render(container, steps, index);
            } else if (action === "reset") {
                index = 0;
                render(container, steps, index);
            }
        });

        // Inițializare
        render(container, steps, index);
    });
})();
