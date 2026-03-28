CREATE DATABASE dbsortari
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE dbsortari;

CREATE TABLE metode (
    id_metoda INT AUTO_INCREMENT PRIMARY KEY,
    nume VARCHAR(50) NOT NULL,
    categorie VARCHAR(50),
    complexitate VARCHAR(50),
    descriere TEXT,
    fisier_cpp VARCHAR(150)
);

CREATE TABLE exercitii (
    id_exercitiu INT AUTO_INCREMENT PRIMARY KEY,
    id_metoda INT NOT NULL,
    titlu VARCHAR(100) NOT NULL,
    enunt TEXT,
    cod_sablon TEXT,
    solutie TEXT,
    nivel ENUM('incepator','mediu','avansat') DEFAULT 'incepator',
    FOREIGN KEY (id_metoda) REFERENCES metode(id_metoda) ON DELETE CASCADE
);

CREATE TABLE rezultate (
    id_rezultat INT AUTO_INCREMENT PRIMARY KEY,
    nume_utilizator VARCHAR(100),
    id_exercitiu INT NOT NULL,
    scor INT,
    data_rezolvare DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_exercitiu) REFERENCES exercitii(id_exercitiu) ON DELETE CASCADE
);

-- Date de test pentru tabelul metode
INSERT INTO metode (nume, categorie, complexitate, descriere, fisier_cpp) VALUES
('Bubble sort', 'comparativa', 'O(n^2)',
 'Sorteaza vectorul prin parcurgeri repetate si interschimbarea elementelor vecine daca sunt in ordine gresita.',
 'BubbleSort.cpp'),
('Sortare prin insertie directa', 'comparativa', 'O(n^2)',
 'Insereaza fiecare element pe pozitia corecta in partea deja sortata a vectorului.',
 'InsertDirect.cpp'),
('Sortare prin insertie binara', 'comparativa', 'O(n^2)',
 'Determina pozitia de inserare folosind cautare binara.',
 'InsertieBinara.cpp'),
('Interclasare', 'comparativa', 'O(n log n)',
 'Combina doua siruri ordonate intr-un singur sir ordonat.',
 'Interclasare.cpp'),
('Interclasare (elemente egale)', 'comparativa', 'O(n log n)',
 'Varianta de interclasare care trateaza explicit elementele egale.',
 'Interclasareegale.cpp'),
('Sortare prin interschimbare simpla', 'comparativa', 'O(n^2)',
 'Compara perechi de elemente si le interschimba daca sunt in ordine gresita.',
 'InterschimbareS.cpp'),
('QuickSort (varianta 1)', 'comparativa', 'O(n log n) in medie',
 'Algoritm de sortare rapida cu pivot si partitionare.',
 'quick1.cpp'),
('QuickSort (varianta clasica)', 'comparativa', 'O(n log n) in medie',
 'Algoritm de sortare rapida cu partitionare clasica.',
 'quicks.cpp');

-- Date de test pentru exercitii
INSERT INTO exercitii (id_metoda, titlu, enunt, cod_sablon, solutie, nivel) VALUES
(1,
 'Bubble sort – completare conditie',
 'Completeaza conditia din if astfel incat vectorul sa fie sortat crescator.',
 'for (int i = 0; i < n - 1; i++) {\n    if (____) {\n        int aux = v[i];\n        v[i] = v[i + 1];\n        v[i + 1] = aux;\n    }\n}',
 'for (int i = 0; i < n - 1; i++) {\n    if (v[i] > v[i + 1]) {\n        int aux = v[i];\n        v[i] = v[i + 1];\n        v[i + 1] = aux;\n    }\n}',
 'incepator'),
(2,
 'Insertie directa – conditie while',
 'Completeaza conditia astfel incat elementele mai mari decat cheia sa fie deplasate spre dreapta.',
 'for (int i = 1; i < n; i++) {\n    int key = v[i];\n    int j = i - 1;\n    while (____) {\n        v[j + 1] = v[j];\n        j--;\n    }\n    v[j + 1] = key;\n}',
 'for (int i = 1; i < n; i++) {\n    int key = v[i];\n    int j = i - 1;\n    while (j >= 0 && v[j] > key) {\n        v[j + 1] = v[j];\n        j--;\n    }\n    v[j + 1] = key;\n}',
 'mediu');
