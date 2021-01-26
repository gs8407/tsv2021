<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../config.php';

date_default_timezone_set('Europe/Belgrade');

$sql = "SELECT id, menadzer, datum, vreme, vreme_zavrsetka, ip_adresa, objekat, razlog, napomena, ocena_izlaganja, ocena_izgleda, ocena_mpo, putanja_slike, opis_naloga1, ime_zaposlenog1, saglasan1, funkcija1, opis_naloga2, ime_zaposlenog2, saglasan2, funkcija2, opis_naloga3, ime_zaposlenog3, saglasan3, funkcija3, opis_naloga4, ime_zaposlenog4, saglasan4, funkcija4, opis_naloga5, ime_zaposlenog5, saglasan5, funkcija5, opis_naloga6, saglasan6, ime_zaposlenog6, funkcija6, opis_naloga7, ime_zaposlenog7, saglasan7, funkcija7, opis_naloga8, ime_zaposlenog8, saglasan8, funkcija8, opis_naloga9, ime_zaposlenog9, saglasan9, funkcija9, opis_naloga10, ime_zaposlenog10, saglasan10, funkcija10 FROM izvestaji ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $izvestaji[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Dnevne aktivnosti menadžera</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($izvestaji as $izvestaj) {?>
                <div class="card mb-4">
                    <div class="card-body">
                        <?php

    echo $izvestaj['id'] . "<br>";
    echo $izvestaj['menadzer'] . "<br>";
    echo date("d-m-Y", strtotime($izvestaj['datum'])) . "<br>";
    echo str_replace(".000000", "",  $izvestaj['vreme']) . "<br>";
    echo str_replace(".000000", "",  $izvestaj['vreme_zavrsetka']) . "<br>";
    echo $izvestaj['ip_adresa'] . "<br>";
    echo $izvestaj['objekat'] . "<br>";
    echo $izvestaj['razlog'] . "<br>";
    echo $izvestaj['napomena'] . "<br>";
    echo $izvestaj['ocena_izlaganja'] . "<br>";
    echo $izvestaj['ocena_izgleda'] . "<br>";
    echo $izvestaj['ocena_mpo'] . "<br>";
    if ($izvestaj['putanja_slike']) {
        echo "<a target='_blank' href='/" . $izvestaj['putanja_slike'] . "'>Pogledaj</a>" . "<br>";
    }
   
    if ($izvestaj['opis_naloga1'] != "") {
        echo "<strong>Nalog 1</strong> <br>";
        echo $izvestaj['opis_naloga1'] . "<br>";
        echo $izvestaj['ime_zaposlenog1'] . "<br>";
        echo $izvestaj['saglasan1'] . "<br>";
        echo $izvestaj['funkcija1'] . "<br>";
    }


    if ($izvestaj['opis_naloga2'] != "") {
        echo "<strong>Nalog 2</strong> <br>";
        echo $izvestaj['opis_naloga2'] . "<br>";
        echo $izvestaj['ime_zaposlenog2'] . "<br>";
        echo $izvestaj['saglasan2'] . "<br>";
        echo $izvestaj['funkcija2'] . "<br>";
    }

    if ($izvestaj['opis_naloga3'] != "") {
        echo "<strong>Nalog 3</strong> <br>";
        echo $izvestaj['opis_naloga3'] . "<br>";
        echo $izvestaj['ime_zaposlenog3'] . "<br>";
        echo $izvestaj['saglasan3'] . "<br>";
        echo $izvestaj['funkcija3'] . "<br>";
    }

    if ($izvestaj['opis_naloga4'] != "") {
        echo "<strong>Nalog 4</strong> <br>";
        echo $izvestaj['opis_naloga4'] . "<br>";
        echo $izvestaj['ime_zaposlenog4'] . "<br>";
        echo $izvestaj['saglasan4'] . "<br>";
        echo $izvestaj['funkcija4'] . "<br>";
    }

    if ($izvestaj['opis_naloga5'] != "") {
        echo "<strong>Nalog 5</strong> <br>";
        echo $izvestaj['opis_naloga5'] . "<br>";
        echo $izvestaj['ime_zaposlenog5'] . "<br>";
        echo $izvestaj['saglasan5'] . "<br>";
        echo $izvestaj['funkcija5'] . "<br>";
    }

    if ($izvestaj['opis_naloga6'] != "") {
        echo "<strong>Nalog 6</strong> <br>";
        echo $izvestaj['opis_naloga6'] . "<br>";
        echo $izvestaj['ime_zaposlenog6'] . "<br>";
        echo $izvestaj['saglasan6'] . "<br>";
        echo $izvestaj['funkcija6'] . "<br>";
    }

    if ($izvestaj['opis_naloga7'] != "") {
        echo "<strong>Nalog 7</strong> <br>";
        echo $izvestaj['opis_naloga7'] . "<br>";
        echo $izvestaj['ime_zaposlenog7'] . "<br>";
        echo $izvestaj['saglasan7'] . "<br>";
        echo $izvestaj['funkcija7'] . "<br>";
    }

    if ($izvestaj['opis_naloga8'] != "") {
        echo "<strong>Nalog 8</strong> <br>";
        echo $izvestaj['opis_naloga8'] . "<br>";
        echo $izvestaj['ime_zaposlenog8'] . "<br>";
        echo $izvestaj['saglasan8'] . "<br>";
        echo $izvestaj['funkcija8'] . "<br>";
    }

    if ($izvestaj['opis_naloga9'] != "") {
        echo "<strong>Nalog 9</strong> <br>";
        echo $izvestaj['opis_naloga9'] . "<br>";
        echo $izvestaj['ime_zaposlenog9'] . "<br>";
        echo $izvestaj['saglasan9'] . "<br>";
        echo $izvestaj['funkcija9'] . "<br>";
    }

    if ($izvestaj['opis_naloga10'] != "") {
        echo "<strong>Nalog 10</strong> <br>";
        echo $izvestaj['opis_naloga10'] . "<br>";
        echo $izvestaj['ime_zaposlenog10'] . "<br>";
        echo $izvestaj['saglasan10'] . "<br>";
        echo $izvestaj['funkcija10'] . "<br>";
    }
 ?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

</body>

</html>