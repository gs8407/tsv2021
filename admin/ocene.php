<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../config.php';

date_default_timezone_set('Europe/Belgrade');

$sql_izgeld = "SELECT id, naziv FROM ocena_izgleda ORDER BY id ASC";
$result = $conn->query($sql_izgeld);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocene_izgleda[] = $row;
    }
}

$sql_izlaganje = "SELECT id, naziv FROM ocena_izlaganja ORDER BY id ASC";
$result = $conn->query($sql_izlaganje);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocene_izlaganja[] = $row;
    }
}

$sql_mpo = "SELECT id, naziv FROM ocena_izlaganja ORDER BY id ASC";
$result = $conn->query($sql_mpo);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocene_mpo[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Admin - Izmene ocena</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Ocene izgleda:</h3>
                        <form id="izgled">
                            <?php foreach ($ocene_izgleda as $ocena_izgleda) { ?>
                            <div class="item mb-3">
                                <input type="number" class="mt-2" value="<?php echo $ocena_izgleda['id'] ?>">
                                <input type="text" class="mt-2" value="<?php echo $ocena_izgleda['naziv'] ?>">
                                <span>x</span>
                            </div>
                            <?php } ?>
                            <input class="btn btn-success mt-3" type="submit" value="Snimi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Ocene izlaganja:</h3>
                        <form id="izlaganje">
                            <?php foreach ($ocene_izlaganja as $ocena_izlaganja) { ?>
                            <div class="item mb-3">
                                <input type="number" class="mt-2" value="<?php echo $ocena_izlaganja['id'] ?>">
                                <input type="text" class="mt-2" value="<?php echo $ocena_izlaganja['naziv'] ?>">
                                <span>x</span>
                            </div>
                            <?php } ?>
                            <input class="btn btn-success mt-3" type="submit" value="Snimi">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <h3>Ocene MPO:</h3>
                        <form id="mpo">
                            <?php foreach ($ocene_mpo as $ocena_mpo) { ?>
                            <div class="item mb-3">
                                <input type="number" class="mt-2" value="<?php echo $ocena_mpo['id'] ?>">
                                <input type="text" class="mt-2" value="<?php echo $ocena_mpo['naziv'] ?>">
                                <span>x</span>
                            </div>
                            <?php } ?>
                            <input class="btn btn-success mt-3" type="submit" value="Snimi">
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>

</body>

</html>