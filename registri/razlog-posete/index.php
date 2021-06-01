<?php
session_start();
//if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//    header("location: /login.php");
//    exit;
//}
error_reporting(E_ALL & ~E_NOTICE);
require_once '../../config.php';

date_default_timezone_set('Europe/Belgrade');

$sql_razlog = "SELECT id, naziv FROM razlog_posete WHERE status = 1 ORDER BY id ASC";
$result = $conn->query($sql_razlog);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $razlozi_posete[] = $row;
    }
}

$sql_razlog_arhiva = "SELECT id, naziv FROM razlog_posete WHERE status = 0 ORDER BY id ASC";
$result = $conn->query($sql_razlog_arhiva);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $razlozi_posete_arhiva[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Registar razloga posete</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .table td, .table th {
            vertical-align: middle;
        }
        .form-group {
            margin: 0;
        }
    </style>
</head>

<body>

<div class="container">
    <img src="../../img/logo.png" class="img-fluid mx-auto mb-4 d-block mt-4"/>
    <h2 class="text-center mb-5">Registar: Razlog posete</h2>
    <div class="card card-container mb-4">
        <div class="card-header">
            <h3 class="mb-0">Aktivni:</h3>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped aktivno">
                <?php
                foreach ($razlozi_posete as $razlog_posete) { ?>
                    <tr>
                        <td><?php echo $razlog_posete["naziv"]; ?></td>
                        <td style="width: 120px">
                            <button class="arhiviraj btn btn-danger" data-id="<?php echo $razlog_posete["id"]; ?>">Arhiviraj</button>
                        </td>
                    </tr>
                <?php }
                ?>
            </table>
        </div>
    </div>
    <div class="card card-container mb-4">
        <div class="card-header">
            <h3 class="mb-0">Arhivirani:</h3>
        </div>
        <div class="card-body">
            <table class="table table-hover table-striped arhivirano">
                <?php
                if ($razlozi_posete_arhiva) {
                    foreach ($razlozi_posete_arhiva as $razlog_posete_arhiva) { ?>
                        <tr>
                            <td><?php echo $razlog_posete_arhiva["naziv"]; ?></td>
                            <td style="width: 120px">
                                <button class="aktiviraj btn btn-success" data-id="<?php echo $razlog_posete_arhiva["id"]; ?>">Aktiviraj</button>
                            </td>
                        </tr>
                    <?php }
                }
                ?>
            </table>
        </div>
    </div>
    <div class="card card-container">
        <div class="card-header">
            <h3 class="mb-0">Dodaj novo:</h3>
        </div>
        <div class="card-body">
            <table class="table">
                <tr>
                    <td style="border: 0">
                        <div class="form-group">
                            <input type="text" class="form-control" id="dodaj" placeholder="Upiši naziv">
                        </div>
                    </td>
                    <td style="width: 120px; border: 0">
                        <button class="dodaj btn btn-info">Dodaj</button>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        $(document).on("click", ".arhiviraj", function (e) {
            let id = $(this).data("id");
            let thisElement = $(this);
            $.ajax({
                url: "izmena.php",
                data: ({"tip": 0, "id": id}),
                success: function (data) {
                    thisElement.closest("tr").remove();
                    thisElement.addClass("aktiviraj btn-success").removeClass("arhiviraj btn-danger").text("Aktiviraj");
                    $(".arhivirano").append(thisElement.closest("tr"));
                }
            });
        });
        $(document).on("click", ".aktiviraj", function (e) {
            let id = $(this).data("id");
            let thisElement = $(this);
            $.ajax({
                url: "izmena.php",
                data: ({"tip": 1, "id": id}),
                success: function (data) {
                    thisElement.closest("tr").remove();
                    thisElement.addClass("arhiviraj btn-danger").removeClass("aktiviraj btn-success").text("Arhiviraj");
                    $(".aktivno").append(thisElement.closest("tr"));

                }
            });
        });
        $(document).on("click", ".dodaj", function (e) {
            let naziv = $("#dodaj").val();
            $.ajax({
                url: "dodaj.php",
                data: ({"naziv": naziv}),
                success: function (data) {
                    $(".aktivno").append('<tr><td>' + naziv + '</td><td style="width: 120px"><button class="btn aktiviraj btn-danger" data-id="' + data + '">Arhiviraj</button></td></tr>').on("click", ".aktiviraj", function (e) {
                        $(this).closest("tr").remove();
                        $(this).addClass("arhiviraj btn-danger");
                        $(this).removeClass("aktiviraj btn-success");
                        $(this).text("Arhiviraj");
                        $(".aktivno").append($(this).closest("tr"));
                    });
                }
            });
            $("#dodaj").val("");
        });
    });
</script>

</body>

</html>