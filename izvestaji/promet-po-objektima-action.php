<?php
// header('Content-Type: application/json; charset=utf-8');

include "../config.php";

$podaci = array();

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

$sql_razlozi = "SELECT id, naziv FROM razlog_posete";
$result = $conn->query($sql_razlozi);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $razlozi[] = $row;
    }
}

$sql_funkcije = "SELECT id, naziv FROM funkcije";
$result = $conn->query($sql_funkcije);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $funkcije[] = $row;
    }
}

$sql = "SELECT
ocena_mpo,
ocena_izlaganja,
ocena_izgleda,
menadzer,
objekat,
IME,
datum,
vreme,
vreme_zavrsetka,
napomena,
razlog,
opis_naloga1,
ime_zaposlenog1,
saglasan1,
funkcija1,
opis_naloga2,
ime_zaposlenog2,
saglasan2,
funkcija2,
opis_naloga3,
ime_zaposlenog3,
saglasan3,
funkcija3,
opis_naloga4,
ime_zaposlenog4,
saglasan4,
funkcija4,
opis_naloga5,
ime_zaposlenog5,
saglasan5,
funkcija5,
opis_naloga6,
ime_zaposlenog6,
saglasan6,
funkcija6,
opis_naloga7,
ime_zaposlenog7,
saglasan7,
funkcija7,
opis_naloga8,
ime_zaposlenog8,
saglasan8,
funkcija8,
opis_naloga9,
ime_zaposlenog9,
saglasan9,
funkcija9,
opis_naloga10,
ime_zaposlenog10,
saglasan10,
funkcija10,
skla.NAZIV
FROM izvestaji
LEFT JOIN korisnik
ON menadzer = korisnik.JAVNA
LEFT JOIN skla
ON skla.SIFRA = objekat
$upiti";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $podaci[] = $row;
    }
}

if ($podaci) {?>

<div id="pdf">
    <div class="container">
        <?php foreach ($podaci as $podatak) {
    foreach ($razlozi as $razlog) {
        $find = $razlog['id'];
        $replace = $razlog['naziv'];
        $arr = $podatak['razlog'];
        $podatak['razlog'] = str_replace($find, $replace, $arr);
    }
    ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Radnja: <?php echo $podatak['objekat']; ?> - <?php echo $podatak['NAZIV']; ?>; Menadžer: <?php echo $podatak['IME']; ?> (<?php echo $podatak['menadzer']; ?>)<br>Login: <?php echo $podatak['datum']; ?> <?php echo str_replace(".000000", "", $podatak['vreme']); ?> Logout: <?php echo $podatak['datum']; ?> <?php echo str_replace(".000000", "", $podatak['vreme_zavrsetka']); ?></h5>
                <p>Razlog posete: <strong><?php echo $podatak['razlog']; ?></strong><br>
                    Ocena opšteg izlaganja robe: <strong><?php echo $podatak['ocena_izlaganja']; ?></strong>; Ocena opšteg izgleda i higijene: <strong><?php echo $podatak['ocena_izgleda']; ?></strong>; Ocena MPO: <strong><?php echo $podatak['ocena_mpo']; ?></strong></p>
                <?php if ($podatak['napomena']) {?>
                <p>Napomena i zapažanja:<br><?php echo $podatak['napomena']; ?></p>
                <?php }?>

                <?php
if ($podatak['opis_naloga1']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga1'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog1'] . ", ";
        echo "Saglasan: " . $podatak['saglasan1'] . ", ";

        foreach ($funkcije as $funkcija) {
            switch ($podatak['funkcija1']) {
                case $funkcija['id']:
                    echo "Funkcija: " . $funkcija['naziv'];
                    break;

                default:
                    # code...
                    break;
            }
        }

    }

    if ($podatak['opis_naloga2']) {
        echo "Nalog 2: <br>" . $podatak['opis_naloga2'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog2'] . ", ";
        echo "Saglasan: " . $podatak['saglasan2'] . ", ";
        echo "Funkcija: " . $podatak['funkcija2'];
    }

    if ($podatak['opis_naloga3']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga3'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog3'] . ", ";
        echo "Saglasan: " . $podatak['saglasan3'] . $podatak['saglasan3'] . ", ";
        echo "Funkcija: " . $podatak['funkcija3'];
    }

    if ($podatak['opis_naloga4']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga4'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog4'] . ", ";
        echo "Saglasan: " . $podatak['saglasan4'] . ", ";
        echo "Funkcija: " . $podatak['funkcija4'];
    }

    if ($podatak['opis_naloga5']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga5'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog5'] . ", ";
        echo "Saglasan: " . $podatak['saglasan5'] . ", ";
        echo "Funkcija: " . $podatak['funkcija5'];
    }

    if ($podatak['opis_naloga6']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga6'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog6'] . ", ";
        echo "Saglasan: " . $podatak['saglasan6'] . ", ";
        echo "Funkcija: " . $podatak['funkcija6'];
    }

    if ($podatak['opis_naloga7']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga7'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog7'] . ", ";
        echo "Saglasan: " . $podatak['saglasan7'] . ", ";
        echo "Funkcija: " . $podatak['funkcija7'];
    }

    if ($podatak['opis_naloga8']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga8'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog8'] . ", ";
        echo "Saglasan: " . $podatak['saglasan8'] . ", ";
        echo "Funkcija: " . $podatak['funkcija8'];
    }

    if ($podatak['opis_naloga9']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga9'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog9'] . ", ";
        echo "Saglasan: " . $podatak['saglasan9'] . ", ";
        echo "Funkcija: " . $podatak['funkcija9'];
    }

    if ($podatak['opis_naloga10']) {
        echo "Nalog 1: <br>" . $podatak['opis_naloga10'] . ", ";
        echo "Zaposleni " . $podatak['ime_zaposlenog10'] . ", ";
        echo "Saglasan: " . $podatak['saglasan10'] . ", ";
        echo "Funkcija: " . $podatak['funkcija10'];
    }
    ?>

            </div>
        </div>
        <?php }

} else {
    echo "<h4 style='text-align: center'>Nema podataka za traženi upit.</h4>";
}

?>

    </div>
</div>