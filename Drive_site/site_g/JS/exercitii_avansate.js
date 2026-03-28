// Exercitii interactive pentru recursivitate si backtracking

var exercitiiAvansate = [
    {
        titlu: "Factorial recursiv - cazul de baza",
        text: "Completeaza conditia pentru cazul de baza.",
        cod: [
            "int fact(int n) {",
            "    if ( ____ ) return 1;",
            "    return n * fact(n - 1);",
            "}"
        ],
        raspunsuri: ["n == 0", "n==0"],
        hint: "Cazul de baza la factorial apare cand n este 0."
    },
    {
        titlu: "Factorial recursiv - autoapel",
        text: "Completeaza expresia recursiva.",
        cod: [
            "int fact(int n) {",
            "    if (n == 0) return 1;",
            "    return ____;",
            "}"
        ],
        raspunsuri: ["n * fact(n - 1)", "n*fact(n-1)"],
        hint: "Inmultesti n cu factorialul pentru n-1."
    },
    {
        titlu: "Fibonacci recursiv - combinarea rezultatelor",
        text: "Completeaza relatia recursiva pentru Fibonacci.",
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
        titlu: "Backtracking permutari - validare",
        text: "Completeaza conditia pentru a evita repetarea valorilor.",
        cod: [
            "bool ok(int k) {",
            "    for (int i = 1; i < k; i++)",
            "        if (____) return false;",
            "    return true;",
            "}"
        ],
        raspunsuri: ["x[i] == x[k]", "x[i]==x[k]"],
        hint: "Nu permitem aceeasi valoare pe doua pozitii diferite."
    },
    {
        titlu: "Backtracking - conditie de solutie",
        text: "Completeaza testul pentru solutia finala la permutari.",
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
        hint: "Cand ai completat toate pozitiile din vectorul solutie."
    }
];

var indexExercitiuAvansat = 0;
var helpClicksAvansat = 0;

function normalizeAdvanced(str) {
    if (typeof str !== "string") return "";
    return str.replace(/\s+/g, "").toLowerCase();
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
        fb.innerText = "Bravo, raspuns corect!";
    } else {
        fb.innerText = "Raspuns gresit. Incearca din nou sau foloseste Ajutor.";
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
        hintElem.innerText = "Sugestie: " + (ex.hint || "Reciteste pasii algoritmului.");
        helpClicksAvansat++;
    } else {
        hintElem.innerText = "O varianta corecta: " + ((ex.raspunsuri && ex.raspunsuri[0]) || "N/A");
    }

    hintElem.style.display = "block";
}

window.addEventListener("load", afiseazaExercitiuAvansat);
