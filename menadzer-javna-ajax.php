<?php
require_once 'config.php';

$objekat = $_REQUEST['objekat'];

if ($result = $conn->query("SELECT SIFRAMENADZERA FROM `skla` WHERE SIFRA = $objekat")) {
  $res = $result->fetch_assoc();
  $siframenadzera = $res["SIFRAMENADZERA"];
  $result->free_result();
}

if ($result = $conn->query("SELECT JAVNASIFRA FROM `putnik` WHERE SIFRA = $siframenadzera")) {
  $res = $result->fetch_assoc();
  $javnamenadzera = $res["JAVNASIFRA"];
  $result->free_result();
}

echo $javnamenadzera;
