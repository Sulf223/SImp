-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 13, 2026 at 01:36 PM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
CREATE DATABASE IF NOT EXISTS `dbsortari`;
USE `dbsortari`;
--
-- Database: `dbsortari`
--

-- --------------------------------------------------------

--
-- Table structure for table `exercitii`
--
DROP TABLE IF EXISTS `exercitii`;
CREATE TABLE IF NOT EXISTS `exercitii` (
  `id_exercitiu` int(11) NOT NULL AUTO_INCREMENT,
  `id_metoda` int(11) NOT NULL,
  `titlu` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enunt` text COLLATE utf8mb4_unicode_ci,
  `cod_sablon` text COLLATE utf8mb4_unicode_ci,
  `solutie` text COLLATE utf8mb4_unicode_ci,
  `nivel` enum('incepator','mediu','avansat') COLLATE utf8mb4_unicode_ci DEFAULT 'incepator',
  PRIMARY KEY (`id_exercitiu`),
  KEY `id_metoda` (`id_metoda`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `exercitii`
--

INSERT INTO `exercitii` (`id_exercitiu`, `id_metoda`, `titlu`, `enunt`, `cod_sablon`, `solutie`, `nivel`) VALUES
(1, 1, 'Bubble sort – completare conditie', 'Completeaza conditia din if astfel incat vectorul sa fie sortat crescator.', 'for (int i = 0; i < n - 1; i++) {\n    if (____) {\n        int aux = v[i];\n        v[i] = v[i + 1];\n        v[i + 1] = aux;\n    }\n}', 'for (int i = 0; i < n - 1; i++) {\n    if (v[i] > v[i + 1]) {\n        int aux = v[i];\n        v[i] = v[i + 1];\n        v[i + 1] = aux;\n    }\n}', 'incepator'),
(2, 2, 'Insertie directa – conditie while', 'Completeaza conditia astfel incat elementele mai mari decat cheia sa fie deplasate spre dreapta.', 'for (int i = 1; i < n; i++) {\n    int key = v[i];\n    int j = i - 1;\n    while (____) {\n        v[j + 1] = v[j];\n        j--;\n    }\n    v[j + 1] = key;\n}', 'for (int i = 1; i < n; i++) {\n    int key = v[i];\n    int j = i - 1;\n    while (j >= 0 && v[j] > key) {\n        v[j + 1] = v[j];\n        j--;\n    }\n    v[j + 1] = key;\n}', 'mediu'),
(3, 1, 'Bubble Sort: Limita buclei', 'Completează limita superioară a buclei `for` pentru a parcurge corect vectorul. Trebuie să ne oprim cu o poziție înainte de final, deoarece comparăm `v[j]` cu `v[j+1]`..', 'for (int i = 0; i < n - 1; i++) {\n    for (int j = 0; j < ____; j++) {\n        if (v[j] > v[j + 1]) {\n            // interschimbare\n        }\n    }\n}', 'n - i - 1', 'incepator'),
(4, 1, 'Bubble Sort: Interschimbarea (partea 1)', 'Completează prima linie a procesului de interschimbare (swap) a două elemente, folosind o variabilă auxiliară.', 'if (v[j] > v[j + 1]) {\n    int aux = ____;\n    v[j] = v[j + 1];\n    v[j + 1] = aux;\n}', 'v[j]', 'incepator'),
(5, 1, 'Bubble Sort: Interschimbarea (partea 2)', 'Completează ultima linie a procesului de interschimbare (swap), unde valoarea din variabila auxiliară este pusă în a doua poziție.', 'if (v[j] > v[j + 1]) {\n    int aux = v[j];\n    v[j] = v[j + 1];\n    v[j + 1] = ____;\n}', 'aux', 'incepator'),
(6, 2, 'Inserție: Alegerea cheii', 'Completează linia care salvează elementul curent într-o variabilă `key`. Acesta este elementul pe care încercăm să-l plasăm în partea deja sortată a vectorului.', 'for (int i = 1; i < n; i++) {\n    int key = ____;\n    int j = i - 1;\n    while (j >= 0 && v[j] > key) {\n        v[j + 1] = v[j];\n        j--;\n    }\n    v[j + 1] = key;\n}', 'v[i]', 'incepator'),
(7, 2, 'Inserție: Deplasarea elementelor', 'Completează linia care mută un element mai mare cu o poziție la dreapta pentru a face loc pentru `key`.', 'int key = v[i];\nint j = i - 1;\nwhile (j >= 0 && v[j] > key) {\n    v[j + 1] = ____;\n    j--;\n}', 'v[j]', 'incepator'),
(8, 2, 'Inserție: Plasarea cheii', 'Completează linia care așează `key` pe poziția sa corectă, după ce elementele mai mari au fost mutate.', 'while (j >= 0 && v[j] > key) {\n    v[j + 1] = v[j];\n    j--;\n}\nv[j + 1] = ____;', 'key', 'incepator'),
(9, 4, 'QuickSort: Alegerea pivotului', 'Completează linia care alege pivotul. În această variantă clasică, de obicei alegem ultimul element din sub-vector ca pivot.', 'int partition(int arr[], int low, int high) {\n    int pivot = ____;\n    int i = (low - 1);\n    // ... restul funcției\n}', 'arr[high]', 'mediu'),
(10, 4, 'QuickSort: Condiția de partiționare', 'Completează condiția `if` care verifică dacă elementul curent este mai mic sau egal cu pivotul, pentru a-l muta în partea stângă.', 'for (int j = low; j <= high - 1; j++) {\n    if (____) {\n        i++;\n        swap(&arr[i], &arr[j]);\n    }\n}', 'arr[j] <= pivot', 'mediu'),
(11, 4, 'QuickSort: Apelul recursiv (stânga)', 'Completează apelul recursiv pentru sub-vectorul din stânga pivotului. Funcția trebuie să se auto-apeleze pentru bucata de dinainte de poziția pivotului.', 'int pi = partition(arr, low, high);\n____;\nquickSort(arr, pi + 1, high);', 'quickSort(arr, low, pi - 1)', 'avansat');

-- --------------------------------------------------------

--
-- Table structure for table `grile_cpp`
--

DROP TABLE IF EXISTS `grile_cpp`;
CREATE TABLE IF NOT EXISTS `grile_cpp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nume_metoda` varchar(255) NOT NULL,
  `dificultate` enum('Usor','Mediu','Greu') DEFAULT 'Usor',
  `intrebare` text NOT NULL,
  `cod_exemplu` text,
  `varianta_1` varchar(255) NOT NULL,
  `varianta_2` varchar(255) NOT NULL,
  `varianta_3` varchar(255) NOT NULL,
  `varianta_4` varchar(255) NOT NULL,
  `raspuns_corect` int(11) NOT NULL COMMENT 'Numărul variantei corecte (1-4)',
  `explicatie` text,
  `doc_link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_grile_doc_link` (`doc_link`(191))
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `grile_cpp`
--

INSERT INTO `grile_cpp` (`id`, `nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`, `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`, `raspuns_corect`, `explicatie`) VALUES
(1, 'Bubble Sort', 'Usor', 'Care este complexitatea în cel mai rău caz (worst-case) pentru algoritmul Bubble Sort?', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'O(1)', 3, 'Bubble Sort are o complexitate O(n^2) în cel mai rău caz, deoarece necesită două bucle imbricate pentru a parcurge și sorta elementele.'),
(2, 'Insertion Sort', 'Usor', 'Ce linie de cod ar trebui plasată în spațiul liber pentru a finaliza corect algoritmul de sortare prin inserție?', 'j = i - 1;\nwhile ((j >= 0) && (a[j] > y)) {\n    a[j+1] = a[j];\n    j--;\n}\n__________;', 'a[j] = y;', 'a[j+1] = y;', 'a[i] = y;', 'a[j-1] = y;', 2, 'După ce elementele mai mari sunt mutate la dreapta, elementul `y` este inserat la poziția corectă, care este `j+1`.'),
(3, 'Quick Sort', 'Mediu', 'Care este rolul principal al funcției `Pozitioneaza` în algoritmul Quick Sort?', 'void Pozitioneaza (int start, int finis, int &k)\n{\n  // ... logica de partiționare ...\n}\n\nvoid Quick (int inceput, int sfarsit)\n{\n  if (inceput < sfarsit)\n  {\n    Pozitioneaza(inceput, sfarsit, k);\n    Quick(inceput, k-1);\n    Quick(k+1, sfarsit);\n  }\n}', 'Sortează complet vectorul', 'Alege un element pivot și rearanjează vectorul astfel încât elementele mai mici să fie la stânga și cele mai mari la dreapta', 'Interclasează doi sub-vectori sortați', 'Găsește elementul minim din vector', 2, 'Funcția de partiționare (aici `Pozitioneaza`) este inima algoritmului Quick Sort, fiind responsabilă pentru plasarea pivotului la locul corect.'),
(4, 'Quick Sort', 'Greu', 'Care este complexitatea în cel mai rău caz (worst-case) pentru Quick Sort și când apare?', NULL, 'O(n^2), când pivotul este mereu cel mai mic sau cel mai mare element', 'O(n log n), mereu', 'O(n), când vectorul este deja sortat', 'O(n^2), când pivotul este ales la întâmplare', 1, 'Cel mai rău caz pentru Quick Sort apare atunci când partiționarea este dezechilibrată, ceea ce duce la o complexitate de O(n^2).'),
(5, 'Counting Sort', 'Mediu', 'Pentru ce tip de date este cel mai potrivit algoritmul de sortare prin numărare (Counting Sort)?', 'for(c=0; c<=99; c++)\n  for(j=1; j<=vf[c]; j++)\n     x[i++] = c;', 'Numere reale (float/double)', 'Șiruri de caractere de lungimi variabile', 'Numere întregi într-un interval restrâns', 'Structuri de date complexe', 3, 'Counting Sort este extrem de eficient, dar funcționează doar pentru numere întregi aflate într-un interval cunoscut și restrâns, deoarece folosește un vector de frecvență.'),
(6, 'Bubble Sort', 'Usor', 'Ce condiție trebuie să fie adevărată pentru a interschimba două elemente?', 'if ( /* ? */ ) { int aux = v[i]; v[i] = v[i+1]; v[i+1] = aux; }', 'v[i] < v[i+1]', 'v[i] > v[i+1]', 'v[i] == v[i+1]', 'v[i] >= v[i+1]', 2, 'Se interschimbă doar dacă elementul din stânga este mai mare decât cel din dreapta.'),
(7, 'Bubble Sort', 'Usor', 'Câte bucle imbricate are implementarea clasică Bubble Sort?', NULL, '1', '2', '3', '4', 2, 'Bubble Sort parcurge vectorul cu două bucle imbricate.'),
(8, 'Bubble Sort', 'Mediu', 'Care este efectul optimizării care oprește parcurgerea când nu au avut loc interschimbări într-o trecere?', NULL, 'Reduce complexitatea la O(n)', 'Oprește algoritmul mai devreme pentru vectori aproape sortați', 'Crește numărul de interschimbări', 'Nu are niciun efect', 2, 'Dacă nu există interschimbări într-o trecere, vectorul este deja sortat.'),
(9, 'Bubble Sort', 'Mediu', 'Ce valoare ia limita interioară a buclei la pasul i?', 'for (int j = 0; j < /* ? */; j++) { ... }', 'n', 'n-1', 'n-i-1', 'n-i', 3, 'Ultimele i elemente sunt deja la locul lor.'),
(10, 'Insertion Sort', 'Usor', 'Ce reprezintă variabila key?', 'int key = /* ? */;', 'v[i]', 'v[j]', 'v[i+1]', 'v[j+1]', 1, 'Se memorează elementul curent pe care vrem să-l inserăm.'),
(11, 'Insertion Sort', 'Mediu', 'Condiția buclei while pentru a deplasa elementele mai mari spre dreapta este:', 'while ( /* ? */ ) { v[j+1]=v[j]; j--; }', 'j>0 && v[j]>key', 'j>=0 && v[j]>key', 'j>=0 || v[j]>key', 'j>0 || v[j]>key', 2, 'Continuăm cât timp nu am ajuns la început și v[j] este mai mare decât key.'),
(12, 'Insertion Sort', 'Usor', 'Unde se inserează key după deplasări?', '// după ieșirea din while\n/* ? */ = key;', 'v[j]', 'v[i]', 'v[j+1]', 'v[i+1]', 3, 'Key se pune pe poziția j+1.'),
(13, 'Insertion Sort', 'Mediu', 'Care este complexitatea medie pentru Insertion Sort?', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'O(log n)', 3, 'Inserția are în medie O(n^2).'),
(14, 'Selection Sort', 'Usor', 'Ce face Selection Sort la fiecare pas?', NULL, 'Alege elementul maxim și îl inserează la final', 'Alege elementul minim și îl aduce pe poziția curentă', 'Împarte vectorul în jumătăți și le combină', 'Mută elementul curent la stânga', 2, 'La fiecare pas selectează minimul din partea nesortată.'),
(15, 'Selection Sort', 'Mediu', 'Care este indicele minimului găsit în bucla internă?', 'int minIdx = i;\nfor (int j=i+1;j<n;j++) { if (v[j] < v[minIdx]) minIdx = /* ? */; }', 'i', 'j', 'minIdx', 'i+1', 2, 'Când găsim un element mai mic, actualizăm minIdx cu j.'),
(16, 'Selection Sort', 'Usor', 'Câte interschimbări maxime face Selection Sort pentru un vector de n elemente?', NULL, 'n', 'n-1', 'n(n-1)/2', 'O(log n)', 2, 'Face cel mult o interschimbare pe pas: n-1 în total.'),
(17, 'Selection Sort', 'Mediu', 'Complexitatea temporală a Selection Sort este:', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'Depinde de ordine', 3, 'Indiferent de date, compară ~n^2/2 perechi.'),
(18, 'Quick Sort', 'Usor', 'Ce returnează funcția de partiționare?', NULL, 'Indicele pivotului plasat corect', 'Numărul de interschimbări', 'Indicele elementului minim', 'Numărul de apeluri recursive', 1, 'După partiționare, pivotul este la poziția returnată.'),
(19, 'Quick Sort', 'Mediu', 'Care este o alegere bună a pivotului pentru a evita worst-case-ul frecvent?', NULL, 'Primul element', 'Ultimul element', 'Element aleator sau median of three', 'Elementul maxim', 3, 'Alegerea aleatorie/mediană tinde să echilibreze partiționarea.'),
(20, 'Quick Sort', 'Greu', 'Când obținem complexitatea O(n^2) la Quick Sort?', NULL, 'Când pivotul este mereu aproape de mediană', 'Când vectorul este deja sortat și pivotul este primul/ultimul', 'Când folosim partiționare Hoare', 'Niciodată', 2, 'Partiționări foarte dezechilibrate duc la O(n^2).'),
(21, 'Quick Sort', 'Mediu', 'După partiționare, sub-vectorii pentru apelurile recursive sunt:', NULL, '[low, pi] și [pi+1, high]', '[low, pi-1] și [pi+1, high]', '[low+1, pi] și [pi, high-1]', '[0, pi-1] și [pi+1, n-1]', 2, 'Pivotul nu mai este inclus în sub-vectori.'),
(22, 'Counting Sort', 'Usor', 'Ce reprezintă k în complexitatea O(n + k)?', NULL, 'Dimensiunea vectorului de frecvență', 'Valoarea maximă din vectorul sortat', 'Numărul de operații de interschimbare', 'Numărul de cifre ale numerelor', 1, 'k este mărimea intervalului de valori posibile.'),
(23, 'Counting Sort', 'Mediu', 'Ce condiție trebuie să fie adevărată pentru a folosi eficient Counting Sort?', NULL, 'Valorile sunt numere reale', 'Valorile sunt întregi într-un interval rezonabil de mic', 'Valorile sunt structuri', 'Valorile sunt șiruri lungi', 2, 'Algoritmul folosește un tablou de frecvență indexat după valoare.'),
(24, 'Counting Sort', 'Mediu', 'Ce se stochează în vectorul de frecvență vf după prima trecere?', 'for (c = 0; c <= maxVal; c++) for (j=1; j<=vf[c]; j++) x[i++] = c;', 'Aparițiile fiecărei valori', 'Suma prefixelor', 'Indicele fiecărui element', 'Poziția finală a fiecărui element', 1, 'Inițial, vf[c] conține numărul de apariții al valorii c.'),
(25, 'Counting Sort', 'Greu', 'La stabilizarea (stable) a Counting Sort, cum se reconstruiește tabloul final?', NULL, 'Se parcurge de la stânga la dreapta și se scade frecvența', 'Se folosesc sume prefix pentru poziții și se parcurge de la dreapta la stânga', 'Se sortează cheile cu un algoritm suplimentar', 'Nu se poate face stabil', 2, 'Sumele prefix dau poziții finale; parcurgerea inversă păstrează stabilitatea.'),
(26, 'Bubble Sort', 'Usor', 'Ce condiție trebuie să fie adevărată pentru a interschimba două elemente?', 'if ( /* ? */ ) { int aux = v[i]; v[i] = v[i+1]; v[i+1] = aux; }', 'v[i] < v[i+1]', 'v[i] > v[i+1]', 'v[i] == v[i+1]', 'v[i] >= v[i+1]', 2, 'Se interschimbă doar dacă elementul din stânga este mai mare decât cel din dreapta.'),
(27, 'Bubble Sort', 'Usor', 'Câte bucle imbricate are implementarea clasică Bubble Sort?', NULL, '1', '2', '3', '4', 2, 'Bubble Sort parcurge vectorul cu două bucle imbricate.'),
(28, 'Bubble Sort', 'Mediu', 'Care este efectul optimizării care oprește parcurgerea când nu au avut loc interschimbări într-o trecere?', NULL, 'Reduce complexitatea la O(n)', 'Oprește algoritmul mai devreme pentru vectori aproape sortați', 'Crește numărul de interschimbări', 'Nu are niciun efect', 2, 'Dacă nu există interschimbări într-o trecere, vectorul este deja sortat.'),
(29, 'Bubble Sort', 'Mediu', 'Ce valoare ia limita interioară a buclei la pasul i?', 'for (int j = 0; j < /* ? */; j++) { ... }', 'n', 'n-1', 'n-i-1', 'n-i', 3, 'Ultimele i elemente sunt deja la locul lor.'),
(30, 'Insertion Sort', 'Usor', 'Ce reprezintă variabila key?', 'int key = /* ? */;', 'v[i]', 'v[j]', 'v[i+1]', 'v[j+1]', 1, 'Se memorează elementul curent pe care vrem să-l inserăm.'),
(31, 'Insertion Sort', 'Mediu', 'Condiția buclei while pentru a deplasa elementele mai mari spre dreapta este:', 'while ( /* ? */ ) { v[j+1]=v[j]; j--; }', 'j>0 && v[j]>key', 'j>=0 && v[j]>key', 'j>=0 || v[j]>key', 'j>0 || v[j]>key', 2, 'Continuăm cât timp nu am ajuns la început și v[j] este mai mare decât key.'),
(32, 'Insertion Sort', 'Usor', 'Unde se inserează key după deplasări?', '// după ieșirea din while\n/* ? */ = key;', 'v[j]', 'v[i]', 'v[j+1]', 'v[i+1]', 3, 'Key se pune pe poziția j+1.'),
(33, 'Insertion Sort', 'Mediu', 'Care este complexitatea medie pentru Insertion Sort?', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'O(log n)', 3, 'Inserția are în medie O(n^2).'),
(34, 'Selection Sort', 'Usor', 'Ce face Selection Sort la fiecare pas?', NULL, 'Alege elementul maxim și îl inserează la final', 'Alege elementul minim și îl aduce pe poziția curentă', 'Împarte vectorul în jumătăți și le combină', 'Mută elementul curent la stânga', 2, 'La fiecare pas selectează minimul din partea nesortată.'),
(35, 'Selection Sort', 'Mediu', 'Care este indicele minimului găsit în bucla internă?', 'int minIdx = i;\nfor (int j=i+1;j<n;j++) { if (v[j] < v[minIdx]) minIdx = /* ? */; }', 'i', 'j', 'minIdx', 'i+1', 2, 'Când găsim un element mai mic, actualizăm minIdx cu j.'),
(36, 'Selection Sort', 'Usor', 'Câte interschimbări maxime face Selection Sort pentru un vector de n elemente?', NULL, 'n', 'n-1', 'n(n-1)/2', 'O(log n)', 2, 'Face cel mult o interschimbare pe pas: n-1 în total.'),
(37, 'Selection Sort', 'Mediu', 'Complexitatea temporală a Selection Sort este:', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'Depinde de ordine', 3, 'Indiferent de date, compară ~n^2/2 perechi.'),
(38, 'Quick Sort', 'Usor', 'Ce returnează funcția de partiționare?', NULL, 'Indicele pivotului plasat corect', 'Numărul de interschimbări', 'Indicele elementului minim', 'Numărul de apeluri recursive', 1, 'După partiționare, pivotul este la poziția returnată.'),
(39, 'Quick Sort', 'Mediu', 'Care este o alegere bună a pivotului pentru a evita worst-case-ul frecvent?', NULL, 'Primul element', 'Ultimul element', 'Element aleator sau median of three', 'Elementul maxim', 3, 'Alegerea aleatorie/mediană tinde să echilibreze partiționarea.'),
(40, 'Quick Sort', 'Greu', 'Când obținem complexitatea O(n^2) la Quick Sort?', NULL, 'Când pivotul este mereu aproape de mediană', 'Când vectorul este deja sortat și pivotul este primul/ultimul', 'Când folosim partiționare Hoare', 'Niciodată', 2, 'Partiționări foarte dezechilibrate duc la O(n^2).'),
(41, 'Quick Sort', 'Mediu', 'După partiționare, sub-vectorii pentru apelurile recursive sunt:', NULL, '[low, pi] și [pi+1, high]', '[low, pi-1] și [pi+1, high]', '[low+1, pi] și [pi, high-1]', '[0, pi-1] și [pi+1, n-1]', 2, 'Pivotul nu mai este inclus în sub-vectori.'),
(42, 'Counting Sort', 'Usor', 'Ce reprezintă k în complexitatea O(n + k)?', NULL, 'Dimensiunea vectorului de frecvență', 'Valoarea maximă din vectorul sortat', 'Numărul de operații de interschimbare', 'Numărul de cifre ale numerelor', 1, 'k este mărimea intervalului de valori posibile.'),
(43, 'Counting Sort', 'Mediu', 'Ce condiție trebuie să fie adevărată pentru a folosi eficient Counting Sort?', NULL, 'Valorile sunt numere reale', 'Valorile sunt întregi într-un interval rezonabil de mic', 'Valorile sunt structuri', 'Valorile sunt șiruri lungi', 2, 'Algoritmul folosește un tablou de frecvență indexat după valoare.'),
(44, 'Counting Sort', 'Mediu', 'Ce se stochează în vectorul de frecvență vf după prima trecere?', 'for (c = 0; c <= maxVal; c++) for (j=1; j<=vf[c]; j++) x[i++] = c;', 'Aparițiile fiecărei valori', 'Suma prefixelor', 'Indicele fiecărui element', 'Poziția finală a fiecărui element', 1, 'Inițial, vf[c] conține numărul de apariții al valorii c.'),
(45, 'Counting Sort', 'Greu', 'La stabilizarea (stable) a Counting Sort, cum se reconstruiește tabloul final?', NULL, 'Se parcurge de la stânga la dreapta și se scade frecvența', 'Se folosesc sume prefix pentru poziții și se parcurge de la dreapta la stânga', 'Se sortează cheile cu un algoritm suplimentar', 'Nu se poate face stabil', 2, 'Sumele prefix dau poziții finale; parcurgerea inversă păstrează stabilitatea.'),
(46, 'Bubble Sort', 'Usor', 'Ce condiție trebuie să fie adevărată pentru a interschimba două elemente?', 'if ( /* ? */ ) { int aux = v[i]; v[i] = v[i+1]; v[i+1] = aux; }', 'v[i] < v[i+1]', 'v[i] > v[i+1]', 'v[i] == v[i+1]', 'v[i] >= v[i+1]', 2, 'Se interschimbă doar dacă elementul din stânga este mai mare decât cel din dreapta.'),
(47, 'Bubble Sort', 'Usor', 'Câte bucle imbricate are implementarea clasică Bubble Sort?', NULL, '1', '2', '3', '4', 2, 'Bubble Sort parcurge vectorul cu două bucle imbricate.'),
(48, 'Bubble Sort', 'Mediu', 'Care este efectul optimizării care oprește parcurgerea când nu au avut loc interschimbări într-o trecere?', NULL, 'Reduce complexitatea la O(n)', 'Oprește algoritmul mai devreme pentru vectori aproape sortați', 'Crește numărul de interschimbări', 'Nu are niciun efect', 2, 'Dacă nu există interschimbări într-o trecere, vectorul este deja sortat.'),
(49, 'Bubble Sort', 'Mediu', 'Ce valoare ia limita interioară a buclei la pasul i?', 'for (int j = 0; j < /* ? */; j++) { ... }', 'n', 'n-1', 'n-i-1', 'n-i', 3, 'Ultimele i elemente sunt deja la locul lor.'),
(50, 'Insertion Sort', 'Usor', 'Ce reprezintă variabila key?', 'int key = /* ? */;', 'v[i]', 'v[j]', 'v[i+1]', 'v[j+1]', 1, 'Se memorează elementul curent pe care vrem să-l inserăm.'),
(51, 'Insertion Sort', 'Mediu', 'Condiția buclei while pentru a deplasa elementele mai mari spre dreapta este:', 'while ( /* ? */ ) { v[j+1]=v[j]; j--; }', 'j>0 && v[j]>key', 'j>=0 && v[j]>key', 'j>=0 || v[j]>key', 'j>0 || v[j]>key', 2, 'Continuăm cât timp nu am ajuns la început și v[j] este mai mare decât key.'),
(52, 'Insertion Sort', 'Usor', 'Unde se inserează key după deplasări?', '// după ieșirea din while\n/* ? */ = key;', 'v[j]', 'v[i]', 'v[j+1]', 'v[i+1]', 3, 'Key se pune pe poziția j+1.'),
(53, 'Insertion Sort', 'Mediu', 'Care este complexitatea medie pentru Insertion Sort?', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'O(log n)', 3, 'Inserția are în medie O(n^2).'),
(54, 'Selection Sort', 'Usor', 'Ce face Selection Sort la fiecare pas?', NULL, 'Alege elementul maxim și îl inserează la final', 'Alege elementul minim și îl aduce pe poziția curentă', 'Împarte vectorul în jumătăți și le combină', 'Mută elementul curent la stânga', 2, 'La fiecare pas selectează minimul din partea nesortată.'),
(55, 'Selection Sort', 'Mediu', 'Care este indicele minimului găsit în bucla internă?', 'int minIdx = i;\nfor (int j=i+1;j<n;j++) { if (v[j] < v[minIdx]) minIdx = /* ? */; }', 'i', 'j', 'minIdx', 'i+1', 2, 'Când găsim un element mai mic, actualizăm minIdx cu j.'),
(56, 'Selection Sort', 'Usor', 'Câte interschimbări maxime face Selection Sort pentru un vector de n elemente?', NULL, 'n', 'n-1', 'n(n-1)/2', 'O(log n)', 2, 'Face cel mult o interschimbare pe pas: n-1 în total.'),
(57, 'Selection Sort', 'Mediu', 'Complexitatea temporală a Selection Sort este:', NULL, 'O(n)', 'O(n log n)', 'O(n^2)', 'Depinde de ordine', 3, 'Indiferent de date, compară ~n^2/2 perechi.'),
(58, 'Quick Sort', 'Usor', 'Ce returnează funcția de partiționare?', NULL, 'Indicele pivotului plasat corect', 'Numărul de interschimbări', 'Indicele elementului minim', 'Numărul de apeluri recursive', 1, 'După partiționare, pivotul este la poziția returnată.'),
(59, 'Quick Sort', 'Mediu', 'Care este o alegere bună a pivotului pentru a evita worst-case-ul frecvent?', NULL, 'Primul element', 'Ultimul element', 'Element aleator sau median of three', 'Elementul maxim', 3, 'Alegerea aleatorie/mediană tinde să echilibreze partiționarea.'),
(60, 'Quick Sort', 'Greu', 'Când obținem complexitatea O(n^2) la Quick Sort?', NULL, 'Când pivotul este mereu aproape de mediană', 'Când vectorul este deja sortat și pivotul este primul/ultimul', 'Când folosim partiționare Hoare', 'Niciodată', 2, 'Partiționări foarte dezechilibrate duc la O(n^2).'),
(61, 'Quick Sort', 'Mediu', 'După partiționare, sub-vectorii pentru apelurile recursive sunt:', NULL, '[low, pi] și [pi+1, high]', '[low, pi-1] și [pi+1, high]', '[low+1, pi] și [pi, high-1]', '[0, pi-1] și [pi+1, n-1]', 2, 'Pivotul nu mai este inclus în sub-vectori.'),
(62, 'Counting Sort', 'Usor', 'Ce reprezintă k în complexitatea O(n + k)?', NULL, 'Dimensiunea vectorului de frecvență', 'Valoarea maximă din vectorul sortat', 'Numărul de operații de interschimbare', 'Numărul de cifre ale numerelor', 1, 'k este mărimea intervalului de valori posibile.'),
(63, 'Counting Sort', 'Mediu', 'Ce condiție trebuie să fie adevărată pentru a folosi eficient Counting Sort?', NULL, 'Valorile sunt numere reale', 'Valorile sunt întregi într-un interval rezonabil de mic', 'Valorile sunt structuri', 'Valorile sunt șiruri lungi', 2, 'Algoritmul folosește un tablou de frecvență indexat după valoare.'),
(64, 'Counting Sort', 'Mediu', 'Ce se stochează în vectorul de frecvență vf după prima trecere?', 'for (c = 0; c <= maxVal; c++) for (j=1; j<=vf[c]; j++) x[i++] = c;', 'Aparițiile fiecărei valori', 'Suma prefixelor', 'Indicele fiecărui element', 'Poziția finală a fiecărui element', 1, 'Inițial, vf[c] conține numărul de apariții al valorii c.'),
(65, 'Counting Sort', 'Greu', 'La stabilizarea (stable) a Counting Sort, cum se reconstruiește tabloul final?', NULL, 'Se parcurge de la stânga la dreapta și se scade frecvența', 'Se folosesc sume prefix pentru poziții și se parcurge de la dreapta la stânga', 'Se sortează cheile cu un algoritm suplimentar', 'Nu se poate face stabil', 2, 'Sumele prefix dau poziții finale; parcurgerea inversă păstrează stabilitatea.');

-- --------------------------------------------------------

--
-- Table structure for table `metode`
--

DROP TABLE IF EXISTS `metode`;
CREATE TABLE IF NOT EXISTS `metode` (
  `id_metoda` int(11) NOT NULL AUTO_INCREMENT,
  `nume` varchar(255) NOT NULL,
  `categorie` varchar(100) DEFAULT NULL,
  `complexitate` varchar(100) DEFAULT NULL,
  `descriere` text,
  `fisier_cpp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_metoda`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `metode`
--

INSERT INTO `metode` (`id_metoda`, `nume`, `categorie`, `complexitate`, `descriere`, `fisier_cpp`) VALUES
(1, 'Bubble Sort', 'Sortare prin interschimbare', 'O(n^2)', 'Bubble Sort este cel mai simplu algoritm de sortare. Parcurge în mod repetat lista, compară elementele adiacente și le interschimbă dacă sunt în ordinea greșită. Procesul se repetă până când lista este sortată. Are o complexitate în cel mai rău caz și în cazul mediu de O(n²), ceea ce îl face ineficient pentru liste mari. Este predominant educațional, folosit pentru a introduce conceptul de sortare.', 'BubbleSort.cpp'),
(2, 'Insertion Sort', 'Sortare prin inserție', 'O(n^2)', 'Insertion Sort construiește tabloul sortat final, un element pe rând. Itrează prin elementele de intrare și, pentru fiecare element, găsește poziția corectă în partea deja sortată a tabloului și îl inserează acolo. Are o complexitate de O(n²), dar este mai eficient în practică decât Bubble Sort și este foarte eficient pentru seturi de date mici sau pentru seturi de date care sunt deja parțial sortate.', 'InsertDirect.cpp'),
(3, 'Selection Sort', 'Sortare prin selecție', 'O(n^2)', 'Selection Sort împarte lista de intrare în două părți: o sublistă sortată, care este construită de la stânga la dreapta, și o sublistă cu elementele nesortate rămase. Algoritmul continuă prin a găsi cel mai mic element din sublista nesortată, îl schimbă cu elementul cel mai din stânga al sublistei nesortate și mută limita sublistelor cu un element la dreapta. Are o complexitate de O(n²) în toate cazurile, fiind simplu de înțeles, dar nu eficient pentru liste mari.', 'Selectie.cpp'),
(4, 'Quick Sort', 'Sortare prin partitionare', 'O(n log n)', 'Quick Sort este un algoritm foarte eficient de tip \'divide et impera\'. Funcționează prin selectarea unui element \'pivot\' din tablou și partiționarea celorlalte elemente în două sub-tablouri, în funcție de faptul dacă sunt mai mici sau mai mari decât pivotul. Sub-tablourile sunt apoi sortate recursiv. Complexitatea sa în cazul mediu este O(n log n), dar în cel mai rău caz este O(n²), dacă pivotul este ales prost.', 'quicks.cpp'),
(5, 'Counting Sort', 'Sortare prin numărare', 'O(n + k)', 'Counting Sort funcționează prin numărarea aparițiilor fiecărui element distinct din tabloul de intrare. Această informație este apoi folosită pentru a plasa elementele direct în pozițiile lor corecte sortate. Este extrem de rapid (complexitate liniară O(n + k)), dar potrivit doar pentru sortarea numerelor întregi într-un interval specific și rezonabil de mic.', 'SortFrecventa.cpp');

-- --------------------------------------------------------

--
-- Table structure for table `progres_grile`
--

DROP TABLE IF EXISTS `progres_grile`;
CREATE TABLE IF NOT EXISTS `progres_grile` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_utilizator` int(11) NOT NULL,
  `id_grila` int(11) NOT NULL,
  `status` enum('completat') NOT NULL DEFAULT 'completat',
  `data_completare` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `progres_unic` (`id_utilizator`,`id_grila`),
  KEY `id_grila` (`id_grila`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `progres_grile`
--

INSERT INTO `progres_grile` (`id`, `id_utilizator`, `id_grila`, `status`, `data_completare`) VALUES
(1, 2, 1, 'completat', '2025-11-19 17:08:32'),
(2, 3, 1, 'completat', '2025-11-23 17:39:23'),
(3, 3, 4, 'completat', '2025-11-23 17:39:56'),
(4, 3, 5, 'completat', '2025-11-23 17:41:12'),
(5, 4, 1, 'completat', '2025-11-25 08:19:46'),
(6, 5, 1, 'completat', '2025-11-26 07:41:29'),
(7, 6, 1, 'completat', '2025-11-26 11:54:30'),
(11, 2, 5, 'completat', '2025-11-26 20:13:44'),
(12, 2, 2, 'completat', '2025-11-27 04:40:53'),
(14, 2, 25, 'completat', '2025-11-27 04:56:42'),
(15, 2, 18, 'completat', '2025-11-27 04:57:15'),
(17, 4, 2, 'completat', '2025-11-27 22:50:40'),
(18, 5, 32, 'completat', '2025-11-29 17:12:32'),
(19, 5, 2, 'completat', '2025-12-02 12:48:51'),
(20, 5, 3, 'completat', '2025-12-02 12:49:43');

-- --------------------------------------------------------

--
-- Table structure for table `rezultate`
--

DROP TABLE IF EXISTS `rezultate`;
CREATE TABLE IF NOT EXISTS `rezultate` (
  `id_rezultat` int(11) NOT NULL AUTO_INCREMENT,
  `nume_utilizator` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_exercitiu` int(11) NOT NULL,
  `scor` int(11) DEFAULT NULL,
  `data_rezolvare` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_rezultat`),
  KEY `id_exercitiu` (`id_exercitiu`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

DROP TABLE IF EXISTS `utilizatori`;
CREATE TABLE IF NOT EXISTS `utilizatori` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `parola_hash` varchar(255) NOT NULL,
  `rol` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`id`, `username`, `parola_hash`, `rol`, `created_at`) VALUES
(1, 'admin', '$2y$10$cR/2xJ5.V.jA4jY1a5a7Y.j/fK/Z.a.z.x.y.Z.a.b.c', 'admin', '2025-11-19 13:00:38'),
(2, 'user', '$2y$10$n5fCrrum5nhtGnlm6cwpWOHAkADSNLXOO6hHkw/wjKLcpjfjKZuH6', 'user', '2025-11-19 13:00:38'),
(3, 'pukbestgf', '$2y$10$62UlYOafxWcg2gOj/czAnuWrUE4493dTnAL5i/brvy7L0VTr0E54G', 'user', '2025-11-23 17:38:26'),
(4, 'sebiboss', '$2y$10$nanQLxsoQEiW5IjBUD9z2ee/Td7C6kzjkXmXDq.Qwwa76p5abroKC', 'user', '2025-11-25 08:18:56'),
(5, 'qwerty12', '$2y$10$DVWR1IcRwIFRGEsm7oXCtOUk1BmVm2XE6LiGrrfPZA1S/peipEwMm', 'user', '2025-11-26 07:41:00'),
(6, 'abp223', '$2y$10$z6kfxLoOHnajZel083mJ9.8yFWmN.s9KCK1O6JX4w4UKkDyfYH/S6', 'user', '2025-11-26 11:54:12');

-- --------------------------------------------------------

--
-- Extensie date: Recursivitate + Backtracking
--

INSERT INTO `metode` (`id_metoda`, `nume`, `categorie`, `complexitate`, `descriere`, `fisier_cpp`)
SELECT
  6,
  'Recursivitate',
  'Tehnica de programare',
  'Depinde de recursie',
  'Functii care se autoapeleaza; include caz de baza, caz recursiv si stiva de apeluri.',
  NULL
WHERE NOT EXISTS (
  SELECT 1
  FROM `metode`
  WHERE `id_metoda` = 6 OR `nume` = 'Recursivitate'
);

INSERT INTO `metode` (`id_metoda`, `nume`, `categorie`, `complexitate`, `descriere`, `fisier_cpp`)
SELECT 7, 'Backtracking', 'Generare/Explorare', 'Exponentiala in general',
     'Construire pas cu pas a unei solutii, cu validare partiala si revenire (pas inapoi).',
     NULL
WHERE NOT EXISTS (SELECT 1 FROM `metode` WHERE `id_metoda` = 7 OR `nume` = 'Backtracking');

INSERT INTO `exercitii` (`id_metoda`, `titlu`, `enunt`, `cod_sablon`, `solutie`, `nivel`)
SELECT 6,
     'Factorial recursiv - caz de baza',
     'Completeaza conditia pentru cazul de baza.',
     'int fact(int n) {\n    if (____) return 1;\n    return n * fact(n - 1);\n}',
     'n == 0',
     'incepator'
WHERE NOT EXISTS (
  SELECT 1 FROM `exercitii` WHERE `titlu` = 'Factorial recursiv - caz de baza'
);

INSERT INTO `exercitii` (`id_metoda`, `titlu`, `enunt`, `cod_sablon`, `solutie`, `nivel`)
SELECT 6,
     'Fibonacci recursiv - formula',
     'Completeaza formula recursiva pentru fibonacci.',
     'int fib(int n) {\n    if (n <= 1) return n;\n    return ____;\n}',
     'fib(n - 1) + fib(n - 2)',
     'mediu'
WHERE NOT EXISTS (
  SELECT 1 FROM `exercitii` WHERE `titlu` = 'Fibonacci recursiv - formula'
);

INSERT INTO `exercitii` (`id_metoda`, `titlu`, `enunt`, `cod_sablon`, `solutie`, `nivel`)
SELECT 7,
     'Backtracking - validare permutari',
     'Completeaza conditia de validare ca sa nu repeti valori in permutare.',
     'bool ok(int k){\n  for(int i = 1; i < k; i++)\n    if (____) return false;\n  return true;\n}',
     'x[i] == x[k]',
     'mediu'
WHERE NOT EXISTS (
  SELECT 1 FROM `exercitii` WHERE `titlu` = 'Backtracking - validare permutari'
);

INSERT INTO `exercitii` (`id_metoda`, `titlu`, `enunt`, `cod_sablon`, `solutie`, `nivel`)
SELECT 7,
     'Backtracking - solutie finala',
     'Completeaza testul pentru solutie finala la permutari.',
     'if (ok(k)) {\n   if (____) afisare();\n   else back(k + 1);\n}',
     'k == n',
     'incepator'
WHERE NOT EXISTS (
  SELECT 1 FROM `exercitii` WHERE `titlu` = 'Backtracking - solutie finala'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Usor',
     'Ce element este obligatoriu intr-o functie recursiva?',
     NULL,
     'O bucla while', 'Un caz de baza', 'Un vector global', 'Un switch',
     2,
     'Fara caz de baza, apelurile recursive nu se opresc.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce element este obligatoriu intr-o functie recursiva?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Mediu',
     'Ce returneaza functia factorial pentru n = 0?',
     'int fact(int n){ if(n==0) return 1; return n*fact(n-1); }',
     '0', '1', '-1', 'nedefinit',
     2,
     'Prin definitie, 0! = 1.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce returneaza functia factorial pentru n = 0?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Mediu',
     'Care este ordinea corecta intr-o functie recursiva?',
     NULL,
     'Caz recursiv, apoi caz de baza',
     'Doar apeluri recursive',
     'Caz de baza si apoi apel recursiv',
     'Nu conteaza ordinea',
     3,
     'Intai verifici oprirea (cazul de baza), apoi continui cu apelul recursiv.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Care este ordinea corecta intr-o functie recursiva?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Greu',
     'Ce complexitate are Fibonacci recursiv simplu (fara memoizare)?',
     'int fib(int n){ if(n<=1) return n; return fib(n-1)+fib(n-2); }',
     'O(n)', 'O(n log n)', 'O(2^n)', 'O(log n)',
     3,
     'Arborele de apeluri creste exponential, aproximativ O(2^n).'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce complexitate are Fibonacci recursiv simplu (fara memoizare)?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Usor',
     'Recursivitatea foloseste in mod direct:',
     NULL,
     'Heap-ul', 'Stiva de apeluri (call stack)', 'Fisierul sursa', 'Memoria video',
     2,
     'Fiecare apel recursiv adauga un nou cadru in call stack.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Recursivitatea foloseste in mod direct:'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Usor',
     'Care este ideea principala in backtracking?',
     NULL,
     'Sortezi datele inainte',
     'Construiesti solutia pas cu pas si revii la o alegere anterioara daca e invalida',
     'Folosesti mereu programare dinamica',
     'Calculezi doar o singura varianta',
     2,
     'Backtracking inseamna explorare cu revenire cand o configuratie nu e valida.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Care este ideea principala in backtracking?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Mediu',
     'In generarea permutarilor, ce verifica de obicei functia ok(k)?',
     'for(int i=1;i<k;i++) if(x[i]==x[k]) return false;',
     'Daca suma este para', 'Daca elementul curent nu a mai fost folosit',
     'Daca vectorul e sortat', 'Daca n este prim',
     2,
     'La permutari, o valoare nu trebuie repetata pe pozitii diferite.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'In generarea permutarilor, ce verifica de obicei functia ok(k)?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Mediu',
     'Cand afisezi o solutie in backtracking?',
     'if(ok(k)){ if(k==n) afisare(); else back(k+1); }',
     'Cand k == 1', 'Cand ok(k) este fals', 'Cand ai completat toate nivelurile (k == n)', 'La fiecare apel',
     3,
     'O solutie completa apare cand ai decis toate pozitiile.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Cand afisezi o solutie in backtracking?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Greu',
     'Ce se intampla dupa ce un ram al cautarii devine invalid?',
     NULL,
     'Algoritmul se opreste definitiv',
     'Se revine la pasul anterior pentru alta alegere',
     'Se sorteaza valorile ramase',
     'Se dubleaza dimensiunea vectorului',
     2,
     'Pasul inapoi este exact mecanismul de backtrack.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce se intampla dupa ce un ram al cautarii devine invalid?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Greu',
     'Care afirmatie este adevarata despre complexitatea backtracking?',
     NULL,
     'Este mereu O(log n)',
     'Este mereu O(n)',
     'Poate fi exponentiala, in functie de spatiul solutiilor',
     'Este mereu O(n log n)',
     3,
     'In multe probleme, numarul de configuratii explorate creste exponential.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Care afirmatie este adevarata despre complexitatea backtracking?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Mediu',
     'Ce este recursivitatea indirecta?',
     NULL,
     'O functie care nu se apeleaza niciodata',
     'O functie care se apeleaza pe ea insasi direct',
     'A apeleaza B, iar B apeleaza A',
     'Apel cu pointeri',
     3,
     'Recursivitatea indirecta implica minim doua functii care se apeleaza circular.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce este recursivitatea indirecta?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Greu',
     'Ce risc apare daca nu ai conditie de oprire intr-o functie recursiva?',
     NULL,
     'Memory leak in heap',
     'Stack overflow',
     'Deadlock',
     'Timeout SQL',
     2,
     'Fara caz de baza, numarul apelurilor creste pana la epuizarea stivei.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce risc apare daca nu ai conditie de oprire intr-o functie recursiva?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Usor',
     'Ce valoare intoarce suma primelor n numere pentru n=1, cu baza corecta?',
     'int s(int n){ if(n==1) return 1; return n + s(n-1); }',
     '0',
     '1',
     '2',
     'n',
     2,
     'Cazul de baza pentru n=1 intoarce 1.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce valoare intoarce suma primelor n numere pentru n=1, cu baza corecta?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Mediu',
     'Ce reprezinta fiecare apel recursiv in executie?',
     NULL,
     'Un element in coada',
     'Un nou cadru in call stack',
     'Un thread nou',
     'Un fisier temporar',
     2,
     'Apelurile recursive se stocheaza in stiva de apeluri.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce reprezinta fiecare apel recursiv in executie?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Recursivitate', 'Greu',
     'Care abordare optimizeaza Fibonacci recursiv?',
     NULL,
     'Sortare rapida',
     'Memoizare / programare dinamica',
     'Backtracking',
     'Counting sort',
     2,
     'Memoizarea evita recalcularea subproblemelor identice.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Care abordare optimizeaza Fibonacci recursiv?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Usor',
     'Care este primul pas cand construiesti o solutie in backtracking?',
     NULL,
     'Verifici si alegi o valoare candidata pentru nivelul curent',
     'Sortezi rezultatul final',
     'Rulezi BFS',
     'Calculezi matricea de adiacenta',
     1,
     'Backtracking construieste solutia incremental, nivel cu nivel.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Care este primul pas cand construiesti o solutie in backtracking?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Mediu',
     'Ce face functia valid in backtracking?',
     NULL,
     'Afiseaza solutia',
     'Verifica daca solutia partiala respecta constrangerile',
     'Sorteaza candidatii',
     'Sterge toate valorile',
     2,
     'Validarea taie ramurile invalide cat mai devreme.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce face functia valid in backtracking?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Greu',
     'Cand se face pasul inapoi (backtrack)?',
     NULL,
     'Cand gasesti prima solutie',
     'Cand solutia partiala devine invalida sau dupa explorarea completa a unei ramuri',
     'Doar la finalul programului',
     'Doar pentru n par',
     2,
     'Revii pentru a incerca alta alegere pe nivelul anterior.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Cand se face pasul inapoi (backtrack)?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Mediu',
     'In problema reginelor, ce verifici la validare?',
     NULL,
     'Doar coloana',
     'Linie, coloana si diagonale atacate',
     'Doar diagonala principala',
     'Numarul total de regine',
     2,
     'Doua regine nu trebuie sa se atace pe aceeasi linie, coloana sau diagonala.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'In problema reginelor, ce verifici la validare?'
);

INSERT INTO `grile_cpp` (`nume_metoda`, `dificultate`, `intrebare`, `cod_exemplu`,
             `varianta_1`, `varianta_2`, `varianta_3`, `varianta_4`,
             `raspuns_corect`, `explicatie`)
SELECT 'Backtracking', 'Greu',
     'Ce avantaj are pruning-ul in backtracking?',
     NULL,
     'Creste memoria folosita',
     'Reduce spatiul de cautare eliminand devreme ramurile imposibile',
     'Face rezultatul aproximativ',
     'Elimina nevoia de recursie',
     2,
     'Pruning-ul reduce semnificativ timpul in multe probleme combinatorii.'
WHERE NOT EXISTS (
  SELECT 1 FROM `grile_cpp`
  WHERE `intrebare` = 'Ce avantaj are pruning-ul in backtracking?'
);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `progres_grile`
--
ALTER TABLE `progres_grile`
  ADD CONSTRAINT `progres_grile_ibfk_1` FOREIGN KEY (`id_utilizator`) REFERENCES `utilizatori` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `progres_grile_ibfk_2` FOREIGN KEY (`id_grila`) REFERENCES `grile_cpp` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
