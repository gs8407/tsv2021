<?php
error_reporting(E_ALL & ~E_NOTICE);
// session_start();
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     header("location: /login.php");
//     exit;
// }

require_once '../config.php';

$sql_razlog = "SELECT id, naziv FROM razlog_posete";
$result_razlog = $conn->query($sql_razlog);
if ($result_razlog->num_rows > 0) {
    while ($row = $result_razlog->fetch_assoc()) {
        $razlozi[] = $row;
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
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">

        <form id="poseta" method="POST" enctype="multipart/form-data">
            <div class="row">

                <form id="poseta" method="POST" enctype="multipart/form-data">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Razlog posete:</label>
                            <select class="form-control" id="razlog" name="razlog">
                                <option disabled selected>-- Izaberi --</option>
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
                </form>

                <div class="col-md-12">
                    <a href="logout.php" class="btn btn-danger btn-lg mt-4">Odustani</a>
                    <input class="btn btn-success float-right btn-lg mt-4" type="submit" value="Završi" id="submit" name="submit">
                </div>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>

</body>

</html>