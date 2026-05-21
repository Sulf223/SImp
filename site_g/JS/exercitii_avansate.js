// Exerciții interactive pentru recursivitate și backtracking

var exercitiiAvansate = [
    {
        titlu: "Factorial recursiv - cazul de bază",
        text: "Completează condiția pentru cazul de bază.",
        cod: [
            "int fact(int n) {",
            "    if ( ____ ) return 1;",
            "    return n * fact(n - 1);",
            "}"
        ],
        raspunsuri: ["n == 0", "n==0"],
        hint: "Cazul de bază la factorial apare când n este 0."
    },
    {
        titlu: "Factorial recursiv - autoapel",
        text: "Completează expresia recursivă.",
        cod: [
            "int fact(int n) {",
            "    if (n == 0) return 1;",
            "    return ____;",
            "}"
        ],
        raspunsuri: ["n * fact(n - 1)", "n*fact(n-1)"],
        hint: "Înmulțești n cu factorialul pentru n-1."
    },
    {
        titlu: "Fibonacci recursiv - combinarea rezultatelor",
        text: "Completează relația recursivă pentru Fibonacci.",
        cod: [
            "int fib(int n) {",
            "    if (n <= 1) return n;",
            "    return ____;",
            "}"
        ],
        raspunsuri: ["fib(n - 1) + fib(n - 2)", "fib(n-1)+fib(n-2)"],
        hint: "Termenul curent este suma celor doi termeni anteriori."
    },
    {
        titlu: "Backtracking permutări - validare",
        text: "Completează condiția pentru a evita repetarea valorilor.",
        cod: [
            "bool ok(int k) {",
            "    for (int i = 1; i < k; i++)",
            "        if (____) return false;",
            "    return true;",
            "}"
        ],
        raspunsuri: ["x[i] == x[k]", "x[i]==x[k]"],
        hint: "Nu permitem aceeași valoare pe două poziții diferite."
    },
    {
        titlu: "Backtracking - condiție de soluție",
        text: "Completează testul pentru soluția finală la permutări.",
        cod: [
            "void back(int k) {",
            "    for (int v = 1; v <= n; v++) {",
            "        x[k] = v;",
            "        if (ok(k)) {",
            "            if (____) afisare();",
            "            else back(k + 1);",
            "        }",
            "    }",
            "}"
        ],
        raspunsuri: ["k == n", "k==n"],
        hint: "Când ai completat toate pozițiile din vectorul soluție."
    }
];

var indexExercitiuAvansat = 0;
var helpClicksAvansat = 0;

function normalizeAdvanced(str) {
    if (typeof str !== "string") return "";
    return str.replace(/\s+/g, "").replace(/;+$/g, "").toLowerCase();
}

function afiseazaExercitiuAvansat() {
    var ex = exercitiiAvansate[indexExercitiuAvansat];
    var container = document.getElementById("exercitiu-avansat-container");
    if (!container) return;

    var html = "<h3>" + ex.titlu + "</h3>";
    html += "<p>" + ex.text + "</p>";
    html += "<pre><code>";

    for (var i = 0; i < ex.cod.length; i++) {
        var linie = ex.cod[i];
        if (linie.indexOf("____") !== -1) {
            html += linie.replace(
                "____",
                "<input type='text' id='raspuns-avansat-" + i + "' size='30'>"
            ) + "\n";
        } else {
            html += linie + "\n";
        }
    }

    html += "</code></pre>";
    container.innerHTML = html;

    var fb = document.getElementById("feedback-avansat");
    if (fb) fb.innerText = "";

    var h = document.getElementById("hint-avansat");
    if (h) {
        h.innerText = "";
        h.style.display = "none";
    }
}

function verificaExercitiuAvansat() {
    var ex = exercitiiAvansate[indexExercitiuAvansat];
    var corect = true;

    for (var i = 0; i < ex.cod.length; i++) {
        if (ex.cod[i].indexOf("____") === -1) continue;

        var inputEl = document.getElementById("raspuns-avansat-" + i);
        if (!inputEl) continue;

        var userInput = normalizeAdvanced(inputEl.value || "");
        var corecte = (ex.raspunsuri || []).map(normalizeAdvanced);
        var esteCorect = corecte.some(function (r) { return r === userInput; });
        if (!esteCorect) {
            corect = false;
            break;
        }
    }

    var fb = document.getElementById("feedback-avansat");
    if (!fb) return;

    if (corect) {
        fb.innerText = "Bravo, răspuns corect!";
    } else {
        fb.innerText = "Răspuns greșit. Încearcă din nou sau folosește Ajutor.";
    }
}

function urmatorulExercitiuAvansat() {
    indexExercitiuAvansat++;
    if (indexExercitiuAvansat >= exercitiiAvansate.length) {
        indexExercitiuAvansat = 0;
    }
    helpClicksAvansat = 0;
    afiseazaExercitiuAvansat();
}

function afiseazaAjutorAvansat() {
    var ex = exercitiiAvansate[indexExercitiuAvansat];
    var hintElem = document.getElementById("hint-avansat");
    if (!hintElem) return;

    if (helpClicksAvansat === 0) {
        hintElem.innerText = "Sugestie: " + (ex.hint || "Recitește pașii algoritmului.");
        helpClicksAvansat++;
    } else {
        hintElem.innerText = "O variantă corectă: " + ((ex.raspunsuri && ex.raspunsuri[0]) || "N/A");
    }

    hintElem.style.display = "block";
}

document.addEventListener("click", function (event) {
    var button = event.target.closest("[data-advanced-exercise-action]");
    if (!button) return;

    var action = button.getAttribute("data-advanced-exercise-action");
    if (action === "check") {
        verificaExercitiuAvansat();
    } else if (action === "next") {
        urmatorulExercitiuAvansat();
    } else if (action === "hint") {
        afiseazaAjutorAvansat();
    }
});

window.addEventListener("load", afiseazaExercitiuAvansat);
