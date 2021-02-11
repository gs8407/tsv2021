<?php
error_reporting(E_ALL & ~E_NOTICE);
// session_start();
// if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
//     header("location: /login.php");
//     exit;
// }
require_once '../config.php';

$sql = "SELECT
AVG(ocena_mpo),
AVG(ocena_izlaganja),
AVG(ocena_izgleda),
COUNT(objekat),
COUNT(id),
menadzer,
objekat,
IME
FROM izvestaji
INNER JOIN korisnik ON menadzer = korisnik.JAVNA
WHERE datum BETWEEN '2021-01-01' AND '2021-01-25'
GROUP BY objekat, menadzer;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $mpo[] = $row;
    }
}

$sql_all = "SELECT COUNT(id) FROM izvestaji;";
$result = $conn->query($sql_all);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $all = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Total po svim objektima</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css"/>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>

    <div class="container">
        <table id="izvestaj" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>
                        Objekat
                    </th>
                    <th>
                        Ukupan broj naloga
                    </th>
                    <th>
                        Menadžer
                    </th>
                    <th>
                        Ocena MPO
                    </th>
                    <th>
                        Ocena Izlaganja
                    </th>
                    <th>
                        Ocena Izgleda</th>
                </tr>
            </thead>
            <tbody>
            <?php
if ($mpo) {
    foreach ($mpo as $izvestaj) {
        // var_dump($izvestaj);
        echo "<tr>";
        echo "<td>" . round($izvestaj["objekat"], 2) . "</td>";
        echo "<td>" . round($izvestaj["COUNT(objekat)"], 2) . "</td>";
        echo "<td>" . $izvestaj["IME"] . "</td>";
        echo "<td>" . round($izvestaj["AVG(ocena_mpo)"], 2) . "</td>";
        echo "<td>" . round($izvestaj["AVG(ocena_izlaganja)"], 2) . "</td>";
        echo "<td>" . round($izvestaj["AVG(ocena_izgleda)"], 2) . "</td>";
        echo "</tr>";
    }
}

?>
</tbody>
<tfoot>
            <tr>
                <th>

                </th>
                <th>
                    Suma: <?php echo $all["COUNT(id)"]; ?>
                </th>
                <th>

                </th>
                <th>

                </th>
                <th>

                </th>
                <th>
                </th>
            </tr>
            </tfoot>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>

    <script>
    $(document).ready(function() {
        $('#izvestaj').DataTable({
            dom: 'Bfrtip',
        buttons: [
            'excelHtml5',
            'pdfHtml5'
        ]
        });
    });
    </script>

</body>

</html>