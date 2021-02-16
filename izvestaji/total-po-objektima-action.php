<?php
// header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
include "../config.php";

if (isset($_POST['menadzer']) && $_POST['menadzer'] != 999999999) {
    $menadzer = "menadzer = " . $_POST["menadzer"];
} else {
    $menadzer = "";
}

if (isset($_POST['objekat']) && $_POST['objekat'] != 999999999) {
    $objekat = "objekat = " . $_POST["objekat"];
} else {
    $objekat = "";
}

if (isset($_POST['razlog']) && $_POST['razlog'] != 999999999) {
    $razlog = "razlog = " . $_POST["razlog"];
} else {
    $razlog = "";
}

if (isset($_POST['ocena_izlaganja_do'])) {
    $ocena_izlaganja = "ocena_izlaganja BETWEEN " . $_POST["ocena_izlaganja_od"] . " AND " . $_POST["ocena_izlaganja_do"];
} else {
    $ocena_izlaganja = "";
}

if (isset($_POST['ocena_izgleda_do'])) {
    $ocena_izgleda = "ocena_izgleda BETWEEN " . $_POST["ocena_izgleda_od"] . " AND " . $_POST["ocena_izgleda_do"];
} else {
    $ocena_izgleda = "";
}

if (isset($_POST['ocena_mpo_do'])) {
    $ocena_mpo = "ocena_mpo BETWEEN " . $_POST["ocena_mpo_od"] . " AND " . $_POST["ocena_mpo_do"];
} else {
    $ocena_mpo = "";
}

if (isset($_POST['datum']) && $_POST['datum'] != "") {
    $datum = "datum BETWEEN '" . date("Y/m/d", strtotime(explode(" - ", $_POST["datum"])[0])) . "' AND '" . date("Y/m/d", strtotime(explode(" - ", $_POST["datum"])[1])) . "'";
} else {
    $datum = "";
}

$upiti_array = array($menadzer, $objekat, $razlog, $ocena_izlaganja, $ocena_izgleda, $ocena_mpo, $datum);
$upiti = implode(" AND ", array_filter($upiti_array));

if ($upiti) {
    $upiti = "WHERE " . $upiti;
}

$sql_ukupno = "SELECT COUNT(id) AS ukupno FROM izvestaji $upiti";
$result = $conn->query($sql_ukupno);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ukupno_naloga = $row['ukupno'];
    }
}

$podaci = array();

$sql = "SELECT
ROUND(AVG(ocena_mpo), 2) AS ocena_mpo,
ROUND(AVG(ocena_izlaganja), 2) AS ocena_izlaganja,
ROUND(AVG(ocena_izgleda), 2) AS ocena_izgleda,
COUNT(vreme) AS broj_naloga,
menadzer,
objekat,
IME,
skla.NAZIV
FROM izvestaji
LEFT JOIN korisnik
ON menadzer = korisnik.JAVNA
LEFT JOIN skla
ON skla.SIFRA = objekat
$upiti
GROUP BY objekat, menadzer, NAZIV;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $podaci[] = $row;
    }

}

if ($podaci) {
    $tabela = '<table id="tabela-prva" class="display table" style="width: 100%">';
    $tabela .= '<thead>';
    $tabela .= '<tr>';
    $tabela .= '<th>Broj radnje</th>';
    $tabela .= '<th>Naziv radnje</th>';
    $tabela .= ' <th>Broj naloga</th>';
    $tabela .= '<th>Menadžer</th>';
    $tabela .= '<th>Srednja ocena izlaganja</th>';
    $tabela .= '<th>Srednja ocena izgleda</th>';
    $tabela .= '<th>Srednja ocena MPO</th>';
    $tabela .= '</tr>';
    $tabela .= ' </thead>';
    $tabela .= '<tbody>';
    foreach ($podaci as $podatak) {
        $tabela .= '<tr>';
        $tabela .= "<td>" . $podatak['objekat'] . "</td>";
        $tabela .= "<td>" . $podatak['NAZIV'] . "</td>";
        $tabela .= "<td>" . $podatak['broj_naloga'] . "</td>";
        $tabela .= "<td>" . $podatak['IME'] . "</td>";
        $tabela .= "<td>" . $podatak['ocena_izlaganja'] . "</td>";
        $tabela .= "<td>" . $podatak['ocena_izgleda'] . "</td>";
        $tabela .= "<td>" . $podatak['ocena_mpo'] . "</td>";
        $tabela .= '</tr>';
    }
    $tabela .= '</tbody>';
    $tabela .= '<tfoot>';
    $tabela .= '<tr>';
    $tabela .= '<th>Broj radnje</th>';
    $tabela .= '<th>Naziv radnje</th>';
    $tabela .= ' <th>Ukupno: ' . $ukupno_naloga . '</th>';
    $tabela .= '<th>Menadžer</th>';
    $tabela .= '<th>Srednja ocena izlaganja</th>';
    $tabela .= '<th>Srednja ocena izgleda</th>';
    $tabela .= '<th>Srednja ocena MPO</th>';
    $tabela .= '</tr>';
    $tabela .= '</tfoot>';
    $tabela .= '</table>';
    echo $tabela;
} else {
    echo "<h4 style='text-align: center'>Nema podataka za traženi upit.</h4>";
}
