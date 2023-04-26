<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Promet po objektima</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">

</head>

<body>
    <div id="spinner"><img src="/img/spinner.gif" alt=""></div>
    <?php
    // header('Content-Type: application/json; charset=utf-8');

    // error_reporting(E_ALL & ~E_NOTICE);
    include "../config.php";

    $podaci = array();

    // if (isset($_GET['menadzer']) && $_GET['menadzer'] != 999999999) {
    //     $menadzer = "menadzer = " . $_GET["menadzer"];
    // } else {
    //     $menadzer = "";
    // }


    if (isset($_GET['ovlascenje'])) {
        if ($_GET['ovlascenje'] == 1) {
            $vrsta_radnika = "zamenik";
            if (isset($_GET['menadzer']) && $_GET['menadzer'] != 999999999) {
                $menadzer = "zamenik = " . $_GET["menadzer"];
            } else {
                $menadzer = "";
            }
        } else {
            $vrsta_radnika = "menadzer";

            if (isset($_GET['menadzer']) && $_GET['menadzer'] != 999999999) {
                $menadzer = "menadzer = " . $_GET["menadzer"];
            } else {
                $menadzer = "";
            }
        }
    }

    if (isset($_GET['izvrsilac'])) {
        if ($_GET['izvrsilac'] == "zamenici") {
            $izvrsilac = "zamenik != 0";
        } else if ($_GET['izvrsilac'] == "vlastiti") {
            $izvrsilac = "zamenik = 0";
        } else {
            $izvrsilac = "";
        }
    } else {
        $izvrsilac = "";
    }

    if (isset($_GET['zamenik']) && $_GET['zamenik'] != 999999999 && $_GET['menadzer'] = 999999999) { 
        $vrsta_radnika = "zamenik";
        $menadzer = "zamenik = " . $_GET["zamenik"];
    }

    if (isset($_GET['objekat']) && $_GET['objekat'] != 999999999) {
        $objekat = "objekat = " . $_GET["objekat"];
    } else {
        $objekat = "";
    }

    if (isset($_GET['razlog']) && $_GET['razlog'] != 999999999) {
        $razlog = "razlog = " . $_GET["razlog"];
    } else {
        $razlog = "";
    }

    if (isset($_GET['ocena_izlaganja_do'])) {
        $ocena_izlaganja = "ocena_izlaganja BETWEEN " . $_GET["ocena_izlaganja_od"] . " AND " . $_GET["ocena_izlaganja_do"];
    } else {
        $ocena_izlaganja = "";
    }

    if (isset($_GET['ocena_izgleda_do'])) {
        $ocena_izgleda = "ocena_izgleda BETWEEN " . $_GET["ocena_izgleda_od"] . " AND " . $_GET["ocena_izgleda_do"];
    } else {
        $ocena_izgleda = "";
    }

    if (isset($_GET['ocena_mpo_do'])) {
        $ocena_mpo = "ocena_mpo BETWEEN " . $_GET["ocena_mpo_od"] . " AND " . $_GET["ocena_mpo_do"];
    } else {
        $ocena_mpo = "";
    }

    if (isset($_GET['datum']) && $_GET['datum'] != "") {
        $datum = "izvestaji.datum BETWEEN '" . date("Y-m-d", strtotime(explode(" - ", $_GET["datum"])[0])) . "' AND '" . date("Y-m-d", strtotime(explode(" - ", $_GET["datum"])[1])) . "'";
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
$upiti ORDER BY izvestaji.datum DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $podaci[] = $row;
        }
    }

    if ($podaci) { ?>
        <div style="height: 100vh; overflow: hidden; opacity: 0">
            <table class="table" id="tabela-prva">
                <thead>
                    <tr>
                        <th>Radnja</th>
                        <th>Menadžer</th>
                        <th>Login</th>
                        <th>Logout</th>
                        <th>IP</th>
                        <th>Razlog posete</th>
                        <th>Ocena opšteg izlaganja robe</th>
                        <th>Ocena opšteg izgleda i higijene</th>
                        <th>Ocena MPO</th>
                        <th>Napomena i zapažanja</th>
                        <th>Nalog 1</th>
                        <th>Nalog 2</th>
                        <th>Nalog 3</th>
                        <th>Nalog 4</th>
                        <th>Nalog 5</th>
                        <th>Nalog 6</th>
                        <th>Nalog 7</th>
                        <th>Nalog 8</th>
                        <th>Nalog 9</th>
                        <th>Nalog 10</th>
                    </tr>
                </thead>
                <tbody>

                    <?php foreach ($podaci as $podatak) {
                        foreach ($razlozi as $razlog) {
                            $find = $razlog['id'];
                            $replace = $razlog['naziv'];
                            $arr = $podatak['razlog'];
                            $podatak['razlog'] = str_replace($find, $replace, $arr);
                        } ?>
                        <tr>
                            <td>
                                <?php echo $podatak['objekat']; ?> - <?php echo $podatak['NAZIV']; ?>
                            </td>
                            <td>
                                <?php echo $podatak['IME']; ?> (<?php echo $podatak['menadzer']; ?>)
                            </td>

                            <td>
                                <?php echo date("d/m/Y", strtotime($podatak['datum'])); ?> <?php echo str_replace(".000000", "", $podatak['vreme']); ?>
                            </td>
                            <td>
                                <?php echo date("d/m/Y", strtotime($podatak['datum'])); ?> <?php echo str_replace(".000000", "", $podatak['vreme_zavrsetka']); ?>
                            </td>
                            <td><?php echo $podatak['ip_adresa']; ?>
                            <td>
                                <?php echo $podatak['razlog']; ?>
                            </td>
                            <td><?php echo $podatak['ocena_izlaganja']; ?>
                            </td>
                            <td><?php echo $podatak['ocena_izgleda']; ?>
                            </td>
                            <td><?php echo $podatak['ocena_mpo']; ?>
                            </td>
                            <td><?php echo $podatak['napomena']; ?>
                            </td>


                            <td>
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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
                                }; ?>
                            </td>
                            <td>
                                <?php
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

                                $putanja_slike = explode(", ", $podatak['putanja_slike']);
                                if ($putanja_slike[0]) {
                                    $i = 1;
                                    foreach ($putanja_slike as $slika) {
                                        echo $_SERVER['SERVER_NAME'] . "/" . $slika . " ";
                                        $i++;
                                    }
                                } ?>
                            </td>

                        </tr>
                <?php
                    }
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
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>
        <script>
            var table = $('#tabela-prva').DataTable({
                "ordering": false,
                drawCallback: function() {
                    $('.buttons-excel').trigger('click');

                },

                initComplete: function(settings, json) {
                    $("#spinner").css('visibility', 'hidden');
                },

                dom: 'Bfrtip',
                buttons: [{
                    extend: 'excelHtml5',
                    download: 'open'

                }],


                customize: function(doc) {
                    //pageMargins [left, top, right, bottom]
                    doc.pageMargins = [50, 20, 50, 20];
                },

                "language": {
                    "sProcessing": "Procesiranje u toku...",
                    "sLengthMenu": "Prikaži _MENU_ elemenata",
                    "sZeroRecords": "Nije pronađen nijedan rezultat",
                    "sInfo": "Prikaz _START_ do _END_ od ukupno _TOTAL_ elemenata",
                    "sInfoEmpty": "Prikaz 0 do 0 od ukupno 0 elemenata",
                    "sInfoFiltered": "(filtrirano od ukupno _MAX_ elemenata)",
                    "sInfoPostFix": "",
                    "sSearch": "Pretraga:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "Početna",
                        "sPrevious": "Prethodna",
                        "sNext": "Sledeća",
                        "sLast": "Poslednja"
                    }
                }

            });
        </script>
</body>