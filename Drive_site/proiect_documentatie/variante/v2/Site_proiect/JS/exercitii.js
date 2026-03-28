// Exerciții inspirate din codurile de sortare C++

var exercitii = [
    {
        titlu: "Bubble sort – condiția din if",
        text: "Completează condiția astfel încât vectorul să fie sortat crescător.",
        cod: [
            "for (int i = 0; i < n - 1; i++) {",
            "    if ( ____ ) {",
            "        int aux = v[i];",
            "        v[i] = v[i + 1];",
            "        v[i + 1] = aux;",
            "    }",
            "}"
        ],
        raspunsuri: ["v[i] > v[i + 1]"],
        hint: "Hint: compară elementul curent cu următorul și fă interschimbarea doar dacă sunt în ordine greșită (descrescător)."
    },
    {
        titlu: "Sortare prin inserție – condiția din while",
        text: "Completează condiția pentru a muta elementele mai mari spre dreapta.",
        cod: [
            "for (int i = 1; i < n; i++) {",
            "    int key = v[i];",
            "    int j = i - 1;",
            "    while ( ____ ) {",
            "        v[j + 1] = v[j];",
            "        j--;",
            "    }",
            "    v[j + 1] = key;",
            "}"
        ],
        raspunsuri: ["j >= 0 && v[j] > key"],
        hint: "Hint: atât timp cât nu ai ajuns la începutul vectorului și elementul din stânga este mai mare decât cheia, trebuie mutat spre dreapta."
    },
    {
        titlu: "Interclasare – alegerea elementului minim",
        text: "Completează condiția astfel încât în vectorul C să fie pus mereu elementul mai mic.",
        cod: [
            "int i = 0, j = 0, k = 0;",
            "while (i < n && j < m) {",
            "    if ( ____ ) {",
            "        C[k++] = A[i++];",
            "    } else {",
            "        C[k++] = B[j++];",
            "    }",
            "}"
        ],
        raspunsuri: ["A[i] <= B[j]"],
        hint: "Hint: compară A[i] cu B[j] și alege în C elementul mai mic (sau egal) pentru a păstra ordinea."
    }
];

var indexCurent = 0;
    var helpClicks = 0;  

function afiseazaExercitiu() {
    var ex = exercitii[indexCurent];
    var container = document.getElementById("exercitiu-container");
    if (!container) return;

    var html = "<h3>" + ex.titlu + "</h3>";
    html += "<p>" + ex.text + "</p>";
    html += "<pre><code>";

    for (var i = 0; i < ex.cod.length; i++) {
        var linie = ex.cod[i];
        if (linie.indexOf("____") !== -1) {
            html += linie.replace("____",
                "<input type='text' id='raspuns" + i + "' size='30'>"
            ) + "\n";
        } else {
            html += linie + "\n";
        }
    }

    html += "</code></pre>";
    container.innerHTML = html;

    // când schimbăm exercițiul, curățăm feedback și hint
    var fb = document.getElementById("feedback");
    if (fb) fb.innerText = "";
    var h = document.getElementById("hint");
    if (h) h.innerText = "";
}

function verificaExercitiu() {
    var ex = exercitii[indexCurent];
    var corect = true;
    var mesaj = "";

    for (var i = 0; i < ex.cod.length; i++) {
        if (ex.cod[i].indexOf("____") !== -1) {
            var inputEl = document.getElementById("raspuns" + i);
            if (!inputEl) continue;
            var userInput = inputEl.value.trim();
            var raspCorect = ex.raspunsuri[0];

            if (userInput === raspCorect) {
                mesaj = "Bravo, răspuns corect!";
            } else {
                mesaj = "Răspuns greșit. Încearcă din nou sau apasă Ajutor pentru o sugestie.";
                corect = false;
            }
        }
    }

    var fb = document.getElementById("feedback");
    if (fb) fb.innerText = mesaj;
}

function urmatorulExercitiu() {
    indexCurent++;
    if (indexCurent >= exercitii.length) {
        indexCurent = 0;
            helpClicks = 0;  // resetăm numărul de clicuri pe Ajutor

    }
    afiseazaExercitiu();
}

function afiseazaAjutor() {
    var ex = exercitii[indexCurent];
    var hintElem = document.getElementById("hint");
    if (!hintElem) return;

    if (helpClicks === 0) {
        // prima apăsare – doar sugestia
        if (ex.hint) {
            hintElem.innerText = "Sugestie: " + ex.hint;
        } else {
            hintElem.innerText = "Sugestie: reia pașii algoritmului din curs și vezi ce condiție lipsește.";
        }
        helpClicks++;
    } else {
        // a doua apăsare – arătăm o soluție posibilă
        if (ex.raspunsuri && ex.raspunsuri[0]) {
            hintElem.innerText = "O posibilă soluție corectă: " + ex.raspunsuri[0];
        } else {
            hintElem.innerText = "Nu există o soluție salvată pentru acest exercițiu.";
        }
    }
}


// la încărcarea paginii
window.addEventListener("load", afiseazaExercitiu);
