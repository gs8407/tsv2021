<?php

include "../config.php";

if(isset($_POST['menadzer'])) {
    $menadzer_query =  "WHERE SIFRAMENADZERA = " . $_POST['menadzer'];
} else {
    $menadzer_query = "";
}

$sql_objekat = "SELECT id, NAZIV, SIFRA, WILDCARD FROM skla $menadzer_query ORDER BY SIFRA";
$result_objekat = $conn->query($sql_objekat);
if ($result_objekat->num_rows > 0) {
    while ($row = $result_objekat->fetch_assoc()) {
        $skladista[] = $row;
        //echo "<option value='" . $skladista['SIFRA'] . "'>" . $skladista['SIFRA'] . " - " . $skladista['NAZIV'] ."></option>";
    }
}
echo "<option value='999999999'>-- Svi --</option>";
foreach($skladista as $skladiste) {
    echo "<option value='" . $skladiste['SIFRA'] . "'>" . $skladiste['SIFRA'] . " - " . $skladiste['NAZIV'] ."</option>";
}