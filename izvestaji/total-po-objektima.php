<?php
error_reporting(E_ALL & ~E_NOTICE);
// session_start();
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     header("location: /login.php");
//     exit;
// }

require_once '../config.php';

$sql_menadzer = "SELECT JAVNASIFRA, NAZIV, SIFRA FROM putnik";
$result = $conn->query($sql_menadzer);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $menadzeri[] = $row;
    }
}

$sql_razlog = "SELECT id, naziv FROM razlog_posete";
$result_razlog = $conn->query($sql_razlog);
if ($result_razlog->num_rows > 0) {
    while ($row = $result_razlog->fetch_assoc()) {
        $razlozi[] = $row;
    }
}

$sql_objekat = "SELECT id, NAZIV, SIFRA FROM skla";
$result_objekat = $conn->query($sql_objekat);
if ($result_objekat->num_rows > 0) {
    while ($row = $result_objekat->fetch_assoc()) {
        $skladista[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Total po objektima</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
</head>

<body>

    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">




        <form id="izvestaj" method="POST" action="total-po-objektima-action.php">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Menadžer:</label>
                        <select class="form-control" id="menadzer" name="menadzer">
                            <option value="999999999">-- Sve --</option>
                            <?php
foreach ($menadzeri as $menadzer) {
    ?>
                            <option value="<?php echo $menadzer['JAVNASIFRA']; ?>" data-menadzer="<?php echo $menadzer['SIFRA']; ?>"><?php echo $menadzer['NAZIV'] ?></option>
                            <?php
}
?>
                        </select>
                        <label for="razlog[]" class="error" style="display: none">Izaberite</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="objekat">Objekat:</label>
                        <select class="form-control" id="objekat" name="objekat">
                            <option value="999999999">-- Sva --</option>
                            <?php
foreach ($skladista as $skladiste) {
    echo "<option value='" . $skladiste["id"] . "'>" . $skladiste["id"] . " - " . $skladiste["NAZIV"] . "</option>";
}
?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Razlog posete:</label>
                        <select class="form-control" id="razlog" name="razlog">
                            <option value="999999999">-- Sve --</option>
                            <?php
foreach ($razlozi as $razlog) {
    ?>
                            <option value="<?php echo $razlog['id']; ?>"><?php echo $razlog['naziv'] ?></option>
                            <?php
}
?>
                        </select>
                        <label for="razlog[]" class="error" style="display: none">Izaberite</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ocena_izlaganja">Ocena opšteg izlaganja robe u MPO:</label>
                        <select class="form-control" id="ocena_izlaganja" name="ocena_izlaganja">
                            <option value="999999999">-- Sve --</option>
                            <?php
//foreach ($ocena_izlaganja as $ocena) {
//    echo "<option value='" . $ocena['id'] . "'>" . $ocena['naziv'] . "</option>";
//}
?>
                            <option value="1">1 - 2</option>
                            <option value="2">2 - 3</option>
                            <option value="3">3 - 4</option>
                            <option value="4">4 - 5</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ocena_izgleda">Ocena opšteg izgleda i higijene MPO:</label>
                        <select class="form-control" id="ocena_izgleda" name="ocena_izgleda">
                            <option value="999999999">-- Sve --</option>
                            <?php
//foreach ($ocena_izgleda as $ocena) {
//    echo "<option value='" . $ocena['id'] . "'>" . $ocena['naziv'] . "</option>";
//}
?>
                            <option value="1">1 - 2</option>
                            <option value="2">2 - 3</option>
                            <option value="3">3 - 4</option>
                            <option value="4">4 - 5</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="ocena_mpo">Ocena MPO:</label>
                        <select class="form-control" id="ocena_mpo" name="ocena_mpo">
                            <option value="999999999">-- Sve --</option>
                            <?php
//foreach ($ocena_mpo as $ocena) {
//    echo "<option value='" . $ocena['id'] . "'>" . $ocena['naziv'] . "</option>";
//}
?>
                            <option value="1">1 - 2</option>
                            <option value="2">2 - 3</option>
                            <option value="3">3 - 4</option>
                            <option value="4">4 - 5</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-12">
                    <!-- <a href="logout.php" class="btn btn-danger btn-lg mt-4">Odustani</a> -->
                    <input class="btn btn-success float-right btn-lg mt-4" type="submit" value="Prikaži" id="submit" name="submit">
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <div id="tabela"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>

    <script>
    $(document).ready(function() {

        $("#menadzer").change(function() {
            var data = {
                menadzer: $('#menadzer').find('option:selected').data('menadzer'),
            }
            $.ajax({
                type: "POST",
                url: "menadzeri-select.php",
                data: data,
                success: function(data) {
                    $("#objekat").html(data);
                }
            });
        });

        $("#izvestaj").submit(function(e) {
            var form = $(this);
            var url = form.attr('action');

            e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(data) {
                    // console.log(data);
                    $('#tabela').html(data);
                  
                    $('#tabela-prva').DataTable();
                   
                }
            });

        });
    });
    </script>

</body>

</html>