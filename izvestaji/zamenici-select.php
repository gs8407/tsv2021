<?php

include "../config.php";


if (isset($_POST['zamenik']) && $_POST['zamenik'] != "999999999") {

    if ($result = $conn->query("SELECT JAVNASIFRA FROM `putnik` WHERE SIFRA = $_POST[zamenik]")) {
        $res = $result->fetch_assoc();
        $javnazamenika = $res["JAVNASIFRA"];
        $result->free_result();
    }

    $menadzer_query =  "WHERE SIFRAZAMENIKA = " . $javnazamenika;
} else {
    $menadzer_query = "";
}

$sql_objekti = "SELECT DISTINCT objekat FROM `izvestaji` WHERE zamenik = $javnazamenika";
$result_objekti = $conn->query($sql_objekti);
if ($result_objekti->num_rows > 0) {
    while ($row = $result_objekti->fetch_assoc()) {
        $objekti[] = $row['objekat'];
    }
}

$objekti_sifre = implode(',', $objekti );

$sql_objekat = "SELECT SIFRA, NAZIV FROM `skla` WHERE SIFRA IN ($objekti_sifre)";
$result_objekat = $conn->query($sql_objekat);
if ($result_objekat->num_rows > 0) {
    while ($row = $result_objekat->fetch_assoc()) {
        $skladista[] = $row;
    }
}

echo "<option value='999999999'>-- Svi --</option>";
foreach($skladista as $skladiste) {
    echo "<option value='" . $skladiste['SIFRA'] . "'>" . $skladiste['SIFRA'] . " - " . $skladiste['NAZIV'] ."</option>";
}