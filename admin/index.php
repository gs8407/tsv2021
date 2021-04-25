<?php
error_reporting(E_ALL & ~E_NOTICE);
require_once '../config.php';

date_default_timezone_set('Europe/Belgrade');

$sql_izgeld = "SELECT id, naziv FROM ocena_izgleda ORDER BY id DESC";
$result = $conn->query($sql_izgeld);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocene_izgleda[] = $row;
    }
}

$sql_izlaganje = "SELECT id, naziv FROM ocena_izlaganja ORDER BY id DESC";
$result = $conn->query($sql_izlaganje);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ocene_izlaganja[] = $row;
    }
}

$sql_mpo = "SELECT id, naziv FROM ocena_izlaganja ORDER BY id DESC";
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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($ocene_izgleda as $ocena_izgleda) {?>
                <div class="card mb-4">
                    <div class="card-body">
                    <input type="text" value="<?php echo $ocena_izgleda['id'] ?>">
                    <input type="text" value="<?php echo $ocena_izgleda['naziv'] ?>">
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($ocene_izlaganja as $ocena_izlaganja) {?>
                <div class="card mb-4">
                    <div class="card-body">
                        <?php
                            echo $ocena_izlaganja['id'] . "<br>";
                            echo $ocena_izlaganja['naziv'] . "<br>";
                        ?>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php foreach ($ocene_mpo as $ocena_mpo) {?>
                <div class="card mb-4">
                    <div class="card-body">
                        <?php
                            echo $ocena_mpo['id'] . "<br>";
                            echo $ocena_mpo['naziv'] . "<br>";
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