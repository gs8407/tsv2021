<?php
// header('Content-Type: application/json; charset=utf-8');
error_reporting(E_ALL & ~E_NOTICE);
include "../config.php";

$podaci = array();

if (isset($_POST['ovlascenje'])) {
    if ($_POST['ovlascenje'] == 1) {
        $vrsta_radnika = "zamenik";
        if (isset($_POST['menadzer']) && $_POST['menadzer'] != 999999999) {
            $menadzer = "zamenik = " . $_POST["menadzer"];
        } else {
            $menadzer = "";
        }
    } else {
        $vrsta_radnika = "menadzer";

        if (isset($_POST['menadzer']) && $_POST['menadzer'] != 999999999) {
            $menadzer = "menadzer = " . $_POST["menadzer"];
        } else {
            $menadzer = "";
        }
    }
}

if (isset($_POST['izvrsilac'])) {
    if ($_POST['izvrsilac'] == "zamenici") {
        $izvrsilac = "zamenik != 0";
    } else if ($_POST['izvrsilac'] == "vlastiti") {
        $izvrsilac = "zamenik = 0";
    } else {
        $izvrsilac = "";
    }
} else {
    $izvrsilac = "";
}


if (isset($_POST['zamenik']) && $_POST['zamenik'] != 999999999 && $_POST['menadzer'] = 999999999) { 
    $vrsta_radnika = "zamenik";
    $menadzer = "zamenik = " . $_POST["zamenik"];
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
    $datum = "izvestaji.datum BETWEEN '" . date("Y-m-d", strtotime(explode(" - ", $_POST["datum"])[0])) . "' AND '" . date("Y-m-d", strtotime(explode(" - ", $_POST["datum"])[1])) . "'";
} else {
    $datum = "";
}

$upiti_array = array($menadzer, $izvrsilac, $objekat, $razlog, $ocena_izlaganja, $ocena_izgleda, $ocena_mpo, $datum);
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
zamenik,
objekat,
IME,
izvestaji.datum,
vreme,
vreme_zavrsetka,
ip_adresa,
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
putanja_slike,
skla.NAZIV
FROM izvestaji
LEFT JOIN korisnik
ON $vrsta_radnika = korisnik.JAVNA
LEFT JOIN skla
ON skla.SIFRA = objekat
$upiti 
ORDER BY izvestaji.datum DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $podaci[] = $row;
    }
}

if ($podaci) { ?>

    <table id="tabela-prva" class="display table dataTable">
        <thead>
            <tr>
                <th>Sadrzaj</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($podaci as $podatak) {
                foreach ($razlozi as $razlog) {
                    $find = $razlog['id'];
                    $replace = $razlog['naziv'];
                    $arr = $podatak['razlog'];
                    $podatak['razlog'] = str_replace($find, $replace, $arr);
                }
            ?>
                <tr>
                    <td>
                        <p style="margin-bottom: 0"><strong>Radnja: <?php echo $podatak['objekat']; ?> - <?php echo $podatak['NAZIV']; ?>; Menadžer: <?php echo $podatak['IME']; ?> (<?php if ($vrsta_radnika == "zamenik") {
                                                                                                                                                                                            echo $podatak['zamenik'];
                                                                                                                                                                                        } else {
                                                                                                                                                                                            echo $podatak['menadzer'];
                                                                                                                                                                                        }; ?>)</strong></p>
                        <p><strong>Login: <?php echo date("d/m/Y", strtotime($podatak['datum'])); ?> <?php echo str_replace(".000000", "", $podatak['vreme']); ?> Logout: <?php echo date("d/m/Y", strtotime($podatak['datum'])); ?> <?php echo str_replace(".000000", "", $podatak['vreme_zavrsetka']) . "\n"; ?></strong></p>
                        <p>IP: <?php echo $podatak['ip_adresa'] ?></p>
                        <p>Razlog posete: <strong><?php echo $podatak['razlog'] . "\n"; ?></strong></p>
                        <p>Ocena opšteg izlaganja robe: <strong><?php echo $podatak['ocena_izlaganja']; ?></strong>; Ocena opšteg izgleda i higijene: <strong><?php echo $podatak['ocena_izgleda']; ?></strong>; Ocena MPO: <strong><?php echo $podatak['ocena_mpo'] .  "\n"; ?></strong></p>
                        <?php if ($podatak['napomena']) { ?>
                            <p>Napomena i zapažanja: <br><?php echo "\n" . $podatak['napomena']; ?></p>
                        <?php } ?>

                        <?php
                        if ($podatak['opis_naloga1']) {
                            echo "<p><strong>Nalog 1: </strong></p>\n<p>" . $podatak['opis_naloga1'] . "</p>\n";

                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog1'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan1'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija1']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga2']) {
                            echo "<p><strong>Nalog 2: </strong></p>\n<p>" . $podatak['opis_naloga2'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog2'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan2'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija2']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga3']) {
                            echo "<p><strong>Nalog 3: </strong></p>\n<p>" . $podatak['opis_naloga3'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog3'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan3'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija3']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga4']) {
                            echo "<p><strong>Nalog 4: </strong></p>\n<p>" . $podatak['opis_naloga4'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog4'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan4'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija4']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga5']) {
                            echo "<p><strong>Nalog 5: </strong></p>\n<p>" . $podatak['opis_naloga5'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog5'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan5'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija5']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga6']) {
                            echo "<p><strong>Nalog 6: </strong></p>\n<p>" . $podatak['opis_naloga6'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog6'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan6'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija6']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga7']) {
                            echo "<p><strong>Nalog 7: </strong></p>\n<p>" . $podatak['opis_naloga7'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog7'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan7'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija7']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga8']) {
                            echo "<p><strong>Nalog 8: </strong></p>\n<p>" . $podatak['opis_naloga8'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog8'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan8'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija8']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga9']) {
                            echo "<p><strong>Nalog 9: </strong></p>\n<p>" . $podatak['opis_naloga9'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog9'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan9'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija9']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };

                        if ($podatak['opis_naloga10']) {
                            echo "<p><strong>Nalog 10: </strong></p>\n<p>" . $podatak['opis_naloga10'] . "</p>\n";
                            echo "<p>Zaposleni: " . $podatak['ime_zaposlenog10'] . ", ";
                            echo "Saglasan: " . $podatak['saglasan10'] . ", ";
                            foreach ($funkcije as $funkcija) {
                                switch ($podatak['funkcija10']) {
                                    case $funkcija['id']:
                                        echo "Funkcija: " . $funkcija['naziv'] . "</p>\n\n";
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
                        };
                        ?>

                        <?php
                        $putanja_slike = explode(", ", $podatak['putanja_slike']);
                        if ($putanja_slike[0]) {
                            $i = 1;
                            foreach ($putanja_slike as $slika) {
                                echo "<a class='d-block mb-1' target='_blank' href='/" . $slika . "'>Slika " . $i . "</a>";
                                $i++;
                            }
                        }
                        ?>
                        <img src="" alt="">

                    </td>
                </tr>
        <?php }
        } else {
            echo "<h4 style='text-align: center'>Nema podataka za traženi upit.</h4>";
        } ?>


        </tbody>
        <tfoot>
            <tr>
                <th></th>
            </tr>
        </tfoot>
    </table>