<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once 'config.php';
date_default_timezone_set('Europe/Belgrade');

if (isset($_POST['submit'])) {
    $menadzer = $_POST['menadzer'];
    $datum = $_POST['datum'];
    $vreme = $_POST['vreme'];
    $vreme_zavrsetka = date("H:i:s");
    $ip_adresa = $_POST['ip_adresa'];
    $objekat = $_POST['objekat'];
    $razlog = implode(",", $_POST['razlog']);
    $napomena = mysqli_real_escape_string($conn, $_POST['napomena']);
    $ocena_izlaganja = $_POST['ocena_izlaganja'];
    $ocena_izgleda = $_POST['ocena_izgleda'];
    $ocena_mpo = $_POST['ocena_mpo'];
    if ($_FILES['file']['tmp_name']) {
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $t = time();
        $stamp = date("his-dmY", $t);
        $slika = 'slike/' . $menadzer . "-" . $stamp . "." . $ext;
        move_uploaded_file($_FILES['file']['tmp_name'], $slika);
        $putanja_slike = $slika;
    } else {
        $putanja_slike = NULL;
    }
    $opis_naloga1 = mysqli_real_escape_string($conn, $_POST['opis_naloga1']);
    $ime_zaposlenog1 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog1']);
    $saglasan1 = $_POST['saglasan1'];
    $funkcija1 = $_POST['funkcija1'];
    $opis_naloga2 = mysqli_real_escape_string($conn, $_POST['opis_naloga2']);
    $ime_zaposlenog2 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog2']);
    $saglasan2 = $_POST['saglasan2'];
    $funkcija2 = $_POST['funkcija2'];
    $opis_naloga3 = mysqli_real_escape_string($conn, $_POST['opis_naloga3']);
    $ime_zaposlenog3 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog3']);
    $saglasan3 = $_POST['saglasan3'];
    $funkcija3 = $_POST['funkcija3'];
    $opis_naloga4 = mysqli_real_escape_string($conn, $_POST['opis_naloga4']);
    $ime_zaposlenog4 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog4']);
    $saglasan4 = $_POST['saglasan4'];
    $funkcija4 = $_POST['funkcija4'];
    $opis_naloga5 = mysqli_real_escape_string($conn, $_POST['opis_naloga5']);
    $ime_zaposlenog5 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog5']);
    $saglasan5 = $_POST['saglasan5'];
    $funkcija5 = $_POST['funkcija5'];
    $opis_naloga6 = mysqli_real_escape_string($conn, $_POST['opis_naloga6']);
    $ime_zaposlenog6 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog6']);
    $saglasan6 = $_POST['saglasan6'];
    $funkcija6 = $_POST['funkcija6'];
    $opis_naloga7 = mysqli_real_escape_string($conn, $_POST['opis_naloga7']);
    $ime_zaposlenog7 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog7']);
    $saglasan7 = $_POST['saglasan7'];
    $funkcija7 = $_POST['funkcija7'];
    $opis_naloga8 = mysqli_real_escape_string($conn, $_POST['opis_naloga8']);
    $ime_zaposlenog8 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog8']);
    $saglasan8 = $_POST['saglasan8'];
    $funkcija8 = $_POST['funkcija8'];
    $opis_naloga9 = mysqli_real_escape_string($conn, $_POST['opis_naloga9']);
    $ime_zaposlenog9 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog9']);
    $saglasan9 = $_POST['saglasan9'];
    $funkcija9 = $_POST['funkcija9'];
    $opis_naloga10 = mysqli_real_escape_string($conn, $_POST['opis_naloga10']);
    $ime_zaposlenog10 = mysqli_real_escape_string($conn, $_POST['ime_zaposlenog10']);
    $saglasan10 = $_POST['saglasan10'];
    $funkcija10 = $_POST['funkcija10'];

    $current_time = date("H:i:s");

    $sql = "INSERT INTO izvestaji (menadzer, datum, vreme, vreme_zavrsetka, ip_adresa, objekat, razlog, napomena, ocena_izlaganja, ocena_izgleda, ocena_mpo, putanja_slike, opis_naloga1, ime_zaposlenog1, saglasan1, funkcija1, opis_naloga2, ime_zaposlenog2, saglasan2, funkcija2, opis_naloga3, ime_zaposlenog3, saglasan3, funkcija3, opis_naloga4, ime_zaposlenog4, saglasan4, funkcija4, opis_naloga5, ime_zaposlenog5, saglasan5, funkcija5, opis_naloga6, ime_zaposlenog6, saglasan6, funkcija6, opis_naloga7, ime_zaposlenog7, saglasan7, funkcija7, opis_naloga8, ime_zaposlenog8, saglasan8, funkcija8, opis_naloga9, ime_zaposlenog9, saglasan9, funkcija9, opis_naloga10, ime_zaposlenog10, saglasan10, funkcija10)
    VALUES ('$menadzer', '$datum', '$vreme', '$vreme_zavrsetka', '$ip_adresa', '$objekat', '$razlog', '$napomena', '$ocena_izlaganja',' $ocena_izgleda', '$ocena_mpo', '$putanja_slike', '$opis_naloga1', '$ime_zaposlenog1', '$saglasan1', '$funkcija1', '$opis_naloga2', '$ime_zaposlenog2', '$saglasan2', '$funkcija2', '$opis_naloga3', '$ime_zaposlenog3', '$saglasan3', '$funkcija3', '$opis_naloga4', '$ime_zaposlenog4', '$saglasan4', '$funkcija4', '$opis_naloga5', '$ime_zaposlenog5', '$saglasan5', '$funkcija5', '$opis_naloga6', '$ime_zaposlenog6', '$saglasan6', '$funkcija6', '$opis_naloga7', '$ime_zaposlenog7', '$saglasan7', '$funkcija7', '$opis_naloga8', '$ime_zaposlenog8', '$saglasan8', '$funkcija8', '$opis_naloga9', '$ime_zaposlenog9', '$saglasan9', '$funkcija9', '$opis_naloga10', '$ime_zaposlenog10', '$saglasan10', '$funkcija10');";

    if (mysqli_query($conn, $sql)) {
        echo "Izveštaj je uspešno upisan u bazu podataka.";
    } else {
        echo "Greška: " . $sql . "<br>" . mysqli_error($conn);
        // $msg = "Greška: " . $sql . "<br>" . mysqli_error($conn);;
        // $msg = wordwrap($msg, 70);
        // mail("gs8407@gmail.com", "TSV aplikacija - Greška pri upisu u bazu", $msg);
    }

    mysqli_close($conn);
}
