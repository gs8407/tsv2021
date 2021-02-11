<?php
// header('Content-Type: application/json; charset=utf-8');

include "../config.php";

if (isset($_POST['menadzer'])) {

    if ($_POST['menadzer'] != 999999999) {
        $menadzer = "menadzer = " . $_POST["menadzer"];
    } else {
        $menadzer = "";
    }
} else {
    $menadzer = "";
}

if (isset($_POST['objekat'])) {
    if ($_POST['objekat'] != 999999999) {
        $objekat = "objekat = " . $_POST["objekat"];
    } else {
        $objekat = "";
    }
} else {
    $objekat = "";
}

if (isset($_POST['razlog'])) {
    if ($_POST['razlog'] != 999999999) {
        $razlog = "razlog = " . $_POST["razlog"];
    } else {
        $razlog = "";
    }
} else {
    $razlog = "";
}

if (isset($_POST['ocena_izlaganja'])) {
    if ($_POST['ocena_izlaganja'] != 999999999) {
        switch ($_POST['ocena_izlaganja']) {
            case '1':
                $ocena_izlaganja = "ocena_izlaganja BETWEEN 1 AND 2";
                break;
            case '2':
                $ocena_izlaganja = "ocena_izlaganja BETWEEN 2 AND 3";
                break;
            case '3':
                $ocena_izlaganja = "ocena_izlaganja BETWEEN 3 AND 4";
                break;
            case '4':
                $ocena_izlaganja = "ocena_izlaganja BETWEEN 4 AND 5";
                break;
            default:
                $ocena_izlaganja = "";
                break;
        }

    } else {
        $ocena_izlaganja = "";
    }
} else {
    $ocena_izlaganja = "";
}

if (isset($_POST['ocena_izgleda'])) {
    if ($_POST['ocena_izgleda'] != 999999999) {
        switch ($_POST['ocena_izgleda']) {
            case '1':
                $ocena_izgleda = "ocena_izgleda BETWEEN 1 AND 2";
                break;
            case '2':
                $ocena_izgleda = "ocena_izgleda BETWEEN 2 AND 3";
                break;
            case '3':
                $ocena_izgleda = "ocena_izgleda BETWEEN 3 AND 4";
                break;
            case '4':
                $ocena_izgleda = "ocena_izgleda BETWEEN 4 AND 5";
                break;
            default:
                $ocena_izgleda = "";
                break;
        }
    } else {
        $ocena_izgleda = "";
    }
} else {
    $ocena_izgleda = "";
}

if (isset($_POST['ocena_mpo'])) {
    if ($_POST['ocena_mpo'] != 999999999) {
        switch ($_POST['ocena_mpo']) {
            case '1':
                $ocena_mpo = "'ocena_mpo BETWEEN 1 AND 2";
                break;
            case '2':
                $ocena_mpo = "'ocena_mpo BETWEEN 2 AND 3";
                break;
            case '3':
                $ocena_mpo = "'ocena_mpo BETWEEN 3 AND 4";
                break;
            case '4':
                $ocena_mpo = "'ocena_mpo BETWEEN 4 AND 5";
                break;
            default:
                $ocena_mpo = "";
                break;
        }
    } else {
        $ocena_mpo = "";
    }
} else {
    $ocena_mpo = "";
}

$upiti_array = array($menadzer, $objekat, $razlog, $ocena_izlaganja, $ocena_izgleda, $ocena_mpo);
$upiti = implode(" AND ", array_filter($upiti_array));

if ($upiti) {
    $upiti = "WHERE " . $upiti;
}

// $sql_ukupno = "SELECT COUNT(id) AS ukupno FROM izvestaji";
// $result = $conn->query($sql_ukupno);
// if ($result->num_rows > 0) {
//     while ($row = $result->fetch_assoc()) {
//         $ukupno_naloga = $row['ukupno'];
//     }
// }

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
    $tabela = '<table id="tabela-prva" class="display" style="width: 100%">';
    $tabela .= '<thead>';
    $tabela .= '<tr>';
    $tabela .= '<th>Broj radnje</th>';
    $tabela .= ' <th>Broj naloga</th>';
    $tabela .= '<th>Menadžer</th>';
    $tabela .= '<th>Srednja ocena MPO</th>';
    $tabela .= '</tr>';
    $tabela .= ' </thead>';
    $tabela .= '<tbody>';
    foreach ($podaci as $podatak) {
        $tabela .= '<tr>';
        $tabela .= "<td>" . $podatak['objekat'] . " - " . $podatak['NAZIV'] . "</td>";
        $tabela .= "<td>" . $podatak['broj_naloga'] . "</td>";
        $tabela .= "<td>" . $podatak['IME'] . "</td>";
        $tabela .= "<td>" . $podatak['ocena_mpo'] . "</td>";
        $tabela .= '</tr>';
    }
    $tabela .= '</tbody>';
    $tabela .= '<tfoot>';
    $tabela .= '<tr>';
    $tabela .= '<th>Broj radnje</th>';
    $tabela .= ' <th>Ukupan broj naloga</th>';
    $tabela .= '<th>Menadžer</th>';
    $tabela .= '<th>Srednja ocena MPO</th>';
    $tabela .= '</tr>';
    $tabela .= '</tfoot>';
    $tabela .= '</table>';
    echo $tabela;
} else {
    echo "Nema podataka za traženi upit.";
}

// UKUPAN BROJ NALOGA I WILDCARD NALOZI
