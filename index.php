<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /login.php");
    exit;
}

require_once 'config.php';

$mpo_query = "SELECT SIFRA, NAZIV FROM putnik WHERE JAVNASIFRA = '$_SESSION[javna]'";
$result = $conn->query($mpo_query);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mpo = $row;
    }
}

date_default_timezone_set('Europe/Belgrade');
function getUserIP()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    } else if (isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    } else if (isset($_SERVER['HTTP_FORWARDED'])) {
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    } else if (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    } else {
        $ipaddress = 'UNKNOWN';
    }

    return $ipaddress;
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
    <?php

$sql_ocena_mpo = "SELECT id, naziv FROM ocena_mpo";
$result = $conn->query($sql_ocena_mpo);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocena_mpo[] = $row;
    }
}

$sql_ocena_izgleda = "SELECT id, naziv FROM ocena_mpo";
$result = $conn->query($sql_ocena_izgleda);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocena_izgleda[] = $row;
    }
}
$sql_ocena_izlaganja = "SELECT id, naziv FROM ocena_izlaganja";
$result = $conn->query($sql_ocena_izlaganja);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocena_izlaganja[] = $row;
    }
}

$sql_razlog = "SELECT id, naziv FROM razlog_posete";
$result_razlog = $conn->query($sql_razlog);
if ($result_razlog->num_rows > 0) {
    while ($row = $result_razlog->fetch_assoc()) {
        $razlozi[] = $row;
    }
}

$sql_objekat = "SELECT id, NAZIV, SIFRA FROM skla WHERE SIFRAMENADZERA = $mpo[SIFRA] OR WILDCARD = 1";
$result_objekat = $conn->query($sql_objekat);
if ($result_objekat->num_rows > 0) {
    while ($row = $result_objekat->fetch_assoc()) {
        $skladista[] = $row;
    }
}

?>
    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h4 class="mb-4 mt-3 text-center"><strong>Datum:</strong>
                    <?php echo date("d-m-Y H:i:s"); ?>
                </h4>
                <h4>Menadžer: <?php echo $mpo["NAZIV"]; ?></h4>
            </div>
        </div>
        <form id="poseta" method="POST" enctype="multipart/form-data">
            <input type="hidden" class="form-control" id="menadzer" name="menadzer" value="<?php echo $_SESSION["javna"]; ?>">
            <input type="hidden" class="form-control" id="datum" name="datum" value="<?php echo date("Y-m-d"); ?>">
            <input type="hidden" class="form-control" id="vreme" name="vreme" value="<?php echo date("H:i:s"); ?>">
            <input type="hidden" class="form-control" id="ip_adresa" name="ip_adresa" value="<?php echo getUserIP(); ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Upload slike</label>
                        <input id="file" type="file" name="file" />
                    </div>
                    <div class="form-group">
                        <label for="objekat">Objekat:</label>
                        <select class="form-control" id="objekat" name="objekat">
                            <option selected disabled>-- Izaberi --</option>
                            <?php
foreach ($skladista as $skladiste) {
    echo "<option value='" . $skladiste["id"] . "'>" . $skladiste["NAZIV"] . "</option>";
}
?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Razlog posete:</label>

                        <?php
foreach ($razlozi as $razlog) {
    ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="razlog<?php echo $razlog['id']; ?>" value="<?php echo $razlog['id']; ?>" name="razlog[]" required>
                            <label class="form-check-label" for="razlog<?php echo $razlog['id']; ?>"><?php echo $razlog['naziv'] ?></label>
                        </div>
                        <?php
}
?>

                        <label for="razlog[]" class="error" style="display: none">Izaberite</label>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="tok-posete">Napomena i zapažanja u toku posete:</label>
                        <textarea class="form-control" id="tok-posete" rows="6" name="napomena"></textarea>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="tok-posete">Nalozi dati zaposlenima:</label>
                    </div>

                    <ul class="nav nav-pills" id="nalozi" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="nalogJedan-tab" data-toggle="tab" href="#nalogJedan" role="tab" aria-controls="nalogJedan" aria-selected="true">1</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogDva-tab" data-toggle="tab" href="#nalogDva" role="tab" aria-controls="nalogDva" aria-selected="true">2</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogTri-tab" data-toggle="tab" href="#nalogTri" role="tab" aria-controls="nalogTri" aria-selected="true">3</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogCetiri-tab" data-toggle="tab" href="#nalogCetiri" role="tab" aria-controls="nalogCetiri" aria-selected="true">4</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogPet-tab" data-toggle="tab" href="#nalogPet" role="tab" aria-controls="nalogPet" aria-selected="true">5</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogSest-tab" data-toggle="tab" href="#nalogSest" role="tab" aria-controls="nalogSest" aria-selected="true">6</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogSedam-tab" data-toggle="tab" href="#nalogSedam" role="tab" aria-controls="nalogSedam" aria-selected="true">7</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogOsam-tab" data-toggle="tab" href="#nalogOsam" role="tab" aria-controls="nalogOsam" aria-selected="true">8</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogDevet-tab" data-toggle="tab" href="#nalogDevet" role="tab" aria-controls="nalogDevet" aria-selected="true">9</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="nalogDeset-tab" data-toggle="tab" href="#nalogDeset" role="tab" aria-controls="nalogDeset" aria-selected="true">10</a>
                        </li>

                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane" id="nalogJedan" role="tabpanel" aria-labelledby="nalogJedan-tab">
                            <h3 class="text-center mt-1">Nalog 1:</h3>
                            <div class="form-group">
                                <label for="opis_naloga1">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga1" name="opis_naloga1"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog1">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog1" name="ime_zaposlenog1">
                            </div>
                            <div class="form-group">
                                <label for="saglasan1">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan1" name="saglasan1">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija1">Funkcija:</label>
                                <select class="form-control" id="funkcija1" name="funkcija1">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogDva" role="tabpanel" aria-labelledby="nalogDva-tab">
                            <h3 class="text-center mt-1">Nalog 2:</h3>
                            <div class="form-group">
                                <label for="opis_naloga2">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga2" name="opis_naloga2"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog2">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog2" name="ime_zaposlenog2">
                            </div>
                            <div class="form-group">
                                <label for="saglasan2">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan2" name="saglasan2">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija2">Funkcija:</label>
                                <select class="form-control" id="funkcija2" name="funkcija2">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogTri" role="tabpanel" aria-labelledby="nalogTri-tab">
                            <h3 class="text-center mt-1">Nalog 3:</h3>
                            <div class="form-group">
                                <label for="opis_naloga3">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga3" name="opis_naloga3"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog3">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog3" name="ime_zaposlenog3">
                            </div>
                            <div class="form-group">
                                <label for="saglasan3">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan3" name="saglasan3">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija3">Funkcija:</label>
                                <select class="form-control" id="funkcija3" name="funkcija3">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogCetiri" role="tabpanel" aria-labelledby="nalogCetiri-tab">
                            <h3 class="text-center mt-1">Nalog 4:</h3>
                            <div class="form-group">
                                <label for="opis_naloga4">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga4" name="opis_naloga4"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog4">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog4" name="ime_zaposlenog4">
                            </div>
                            <div class="form-group">
                                <label for="saglasan4">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan4" name="saglasan4">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija4">Funkcija:</label>
                                <select class="form-control" id="funkcija4" name="funkcija4">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogPet" role="tabpanel" aria-labelledby="nalogPet-tab">
                            <h3 class="text-center mt-1">Nalog 5:</h3>
                            <div class="form-group">
                                <label for="opis_naloga5">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga5" name="opis_naloga5"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog5">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog5" name="ime_zaposlenog5">
                            </div>
                            <div class="form-group">
                                <label for="saglasan5">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan5" name="saglasan5">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija5">Funkcija:</label>
                                <select class="form-control" id="funkcija5" name="funkcija5">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogSest" role="tabpanel" aria-labelledby="nalogSest-tab">
                            <h3 class="text-center mt-1">Nalog 6:</h3>
                            <div class="form-group">
                                <label for="opis_naloga6">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga6" name="opis_naloga6"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog6">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog6" name="ime_zaposlenog6">
                            </div>
                            <div class="form-group">
                                <label for="saglasan6">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan6" name="saglasan6">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija6">Funkcija:</label>
                                <select class="form-control" id="funkcija6" name="funkcija6">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogSedam" role="tabpanel" aria-labelledby="nalogSedam-tab">
                            <h3 class="text-center mt-1">Nalog 7:</h3>
                            <div class="form-group">
                                <label for="opis_naloga7">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga7" name="opis_naloga7"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog7">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog7" name="ime_zaposlenog7">
                            </div>
                            <div class="form-group">
                                <label for="saglasan7">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan7" name="saglasan7">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija7">Funkcija:</label>
                                <select class="form-control" id="funkcija7" name="funkcija7">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogOsam" role="tabpanel" aria-labelledby="nalogOsam-tab">
                            <h3 class="text-center mt-1">Nalog 8:</h3>
                            <div class="form-group">
                                <label for="opis_naloga8">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga8" name="opis_naloga8"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog8">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog8" name="ime_zaposlenog8">
                            </div>
                            <div class="form-group">
                                <label for="saglasan8">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan8" name="saglasan8">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija8">Funkcija:</label>
                                <select class="form-control" id="funkcija8" name="funkcija8">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogDevet" role="tabpanel" aria-labelledby="nalogDevet-tab">
                            <h3 class="text-center mt-1">Nalog 9:</h3>
                            <div class="form-group">
                                <label for="opis_naloga9">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga9" name="opis_naloga9"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog9">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog9" name="ime_zaposlenog9">
                            </div>
                            <div class="form-group">
                                <label for="saglasan9">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan9" name="saglasan9">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija10">Funkcija:</label>
                                <select class="form-control" id="funkcija9" name="funkcija9">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                        <div class="tab-pane" id="nalogDeset" role="tabpanel" aria-labelledby="nalogDeset-tab">
                            <h3 class="text-center mt-1">Nalog 10:</h3>
                            <div class="form-group">
                                <label for="opis_naloga10">Opis naloga:</label>
                                <textarea type="text" class="form-control" rows="5" id="opis_naloga10" name="opis_naloga10"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="ime_zaposlenog10">Ime i prezime zaposlenog:</label>
                                <input type="text" class="form-control" id="ime_zaposlenog10" name="ime_zaposlenog10">
                            </div>
                            <div class="form-group">
                                <label for="saglasan10">Zaposleni saglasna sa nalogom:</label>
                                <select class="form-control" id="saglasan10" name="saglasan10">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="da">DA</option>
                                    <option value="ne">NE</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="funkcija10">Funkcija:</label>
                                <select class="form-control" id="funkcija10" name="funkcija10">
                                    <option disabled selected>-- Izaberi --</option>
                                    <option value="1">Poslovođa</option>
                                    <option value="2">Prodavac</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ocena_izlaganja">Ocena opšteg izlaganja robe u MPO:</label>
                        <select class="form-control" id="ocena_izlaganja" name="ocena_izlaganja">
                            <option disabled selected>-- Izaberi --</option>
                            <?php
foreach ($ocena_izlaganja as $ocena) {
    echo "<option value='" . $ocena['id'] . "'>" . $ocena['naziv'] . "</option>";
}
?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ocena_izgleda">Ocena opšteg izgleda i higijene MPO:</label>
                        <select class="form-control" id="ocena_izgleda" name="ocena_izgleda">
                            <option disabled selected>-- Izaberi --</option>
                            <?php
foreach ($ocena_izgleda as $ocena) {
    echo "<option value='" . $ocena['id'] . "'>" . $ocena['naziv'] . "</option>";
}
?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="ocena_mpo">Ocena MPO:</label>
                        <select class="form-control" id="ocena_mpo" name="ocena_mpo">
                            <option disabled selected>-- Izaberi --</option>
                            <?php
foreach ($ocena_mpo as $ocena) {
    echo "<option value='" . $ocena['id'] . "'>" . $ocena['naziv'] . "</option>";
}
?>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <a href="logout.php" class="btn btn-danger btn-lg mt-4">Odustani</a>
                    <input class="btn btn-success float-right btn-lg mt-4" type="submit" value="Završi" id="submit" name="submit">
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script>
    $("#poseta").validate({

        rules: {
            objekat: "required",
            razlog: "required",
            ocena_izlaganja: "required",
            ocena_izgleda: "required",
            ocena_mpo: "required",
            "razlog[]": {
                required: true,
                minlength: 1
            }
        }, // end rules
        messages: {
            objekat: "Izaberite",
            "razlog[]": "Izaberite",
            ocena_izlaganja: "Izaberite",
            ocena_izgleda: "Izaberite",
            ocena_mpo: "Izaberite",

        }, // end messages

        submitHandler: function(form) {
            var submitButton = $("#submit");
            var message = $('#poruka');
            var formData = new FormData($("#poseta")[0]);
            formData.append('image', $('#file')[0].files[0]); 

            $.ajax({
                url: 'izvestaj-ajax.php', 
                type: 'POST', 
                enctype: 'multipart/form-data',
                data: formData,
                processData: false,
                contentType: false,
                beforeSend: function() {
                    // message.fadeOut();
                    submitButton.html('Snima se....'); // change submit button text
                    $("input#submit").val("Snima se..").prop("disabled", true);
                },
                success: function(data) {
                    $("input#submit").hide();
                    var response = data;
                    // form.trigger('reset'); // reset form
                    message.html(data);
                    $('#uspesno').modal('show');
                },
                error: function(e) {
                    console.log(e)
                }
            });

        } // end submit handler

    }); //end validate
    </script>

    <div class="modal" tabindex="-1" role="dialog" id="uspesno">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <p>
                    <div id="poruka"></div>
                    </p>
                </div>
                <div class="modal-footer">
                    <a href="logout.php"><button type="button" class="btn btn-primary">OK</button></a>
                </div>
            </div>
        </div>
    </div>

</body>

</html>