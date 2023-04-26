<?php
error_reporting(E_ALL & ~E_NOTICE);
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: /izvestaji/login.php");
    exit;
}

require_once '../config.php';
if ($_SESSION["ovlascenje"] == 4) {
    $sql_menadzer = "SELECT JAVNASIFRA, putnik.NAZIV, putnik.SIFRA FROM putnik LEFT JOIN korisnik ON JAVNASIFRA = korisnik.JAVNA WHERE korisnik.OVLASCENJE = 2";
    $result = $conn->query($sql_menadzer);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menadzeri[] = $row;
        }
    }

    $sql_zamenik = "SELECT JAVNASIFRA, putnik.NAZIV, putnik.SIFRA FROM putnik LEFT JOIN korisnik ON JAVNASIFRA = korisnik.JAVNA WHERE korisnik.OVLASCENJE = 1";
    $result = $conn->query($sql_zamenik);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $zamenici[] = $row;
        }
    }
} else {
    $sql_menadzer = "SELECT JAVNASIFRA, NAZIV, SIFRA FROM putnik WHERE JAVNASIFRA = '$_SESSION[javna]'";
    $result = $conn->query($sql_menadzer);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $menadzer = $row;
        }
    }
}

$sql_razlog = "SELECT id, naziv FROM razlog_posete";
$result_razlog = $conn->query($sql_razlog);
if ($result_razlog->num_rows > 0) {
    while ($row = $result_razlog->fetch_assoc()) {
        $razlozi[] = $row;
    }
}

if ($_SESSION["ovlascenje"] == 4) {
    $sql_objekat = "SELECT id, NAZIV, SIFRA FROM skla ORDER BY SIFRA";
    $result_objekat = $conn->query($sql_objekat);
    if ($result_objekat->num_rows > 0) {
        while ($row = $result_objekat->fetch_assoc()) {
            $skladista[] = $row;
        }
    }
} else if ($_SESSION["ovlascenje"] == 1) {
    $sql_objekat = "SELECT id, NAZIV, SIFRA FROM skla WHERE FIND_IN_SET($menadzer[SIFRA],SIFRAZAMENIKA) OR WILDCARD = 1 ORDER BY SIFRA";
    $result_objekat = $conn->query($sql_objekat);
    if ($result_objekat->num_rows > 0) {
        while ($row = $result_objekat->fetch_assoc()) {
            $skladista[] = $row;
        }
    }
} else {
    $sql_objekat = "SELECT id, NAZIV, SIFRA FROM skla WHERE SIFRAMENADZERA = '$menadzer[SIFRA]' OR WILDCARD = 1 ORDER BY SIFRA";
    $result_objekat = $conn->query($sql_objekat);
    if ($result_objekat->num_rows > 0) {
        while ($row = $result_objekat->fetch_assoc()) {
            $skladista[] = $row;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSV Diskont - Promet po objektima</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <style>
        button.dt-button {
            background: transparent;
            border: 2px solid #ff7713;
            border-radius: 5px;
            font-size: 16px;
            padding: 5px 15px;
        }

        button.dt-button:hover {
            background: #ff7713;
            color: #fff;
        }

        .ocene-container input {
            max-width: 60px;
        }

        .ocene-container label:first-child input {
            margin-right: 15px
        }

        .ocene-container label {
            margin-top: 0;
        }

        th.sorting_asc {
            display: none;
        }

        iframe {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            display: block;
        }
    </style>
</head>

<body>

    <div class="container">
        <img src="/img/logo.png" class="img-fluid mx-auto d-block my-3" alt="">
    </div>
    <div class="container">

        <form id="izvestaj" method="POST" action="promet-po-objektima-action.php">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Menadžer:</label>
                        <input type="hidden" name="ovlascenje" value="<?php echo $_SESSION["ovlascenje"]; ?>">
                        <?php if ($_SESSION["ovlascenje"] == 4) { ?>
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
                        <?php } else { ?>
                            <div><?php echo $_SESSION["ime"]; ?><input type="text" value="<?php echo $_SESSION["javna"]; ?>" name="menadzer" hidden></div>
                        <?php } ?>
                    </div>
                </div>
                <?php if ($_SESSION["ovlascenje"] == 4) { ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Zamenik:</label>
                        <input type="hidden" name="ovlascenje" value="<?php echo $_SESSION["ovlascenje"]; ?>">
                        <?php if ($_SESSION["ovlascenje"] == 4) { ?>
                            <select class="form-control" id="zamenik" name="zamenik">
                                <option value="999999999">-- Sve --</option>
                                <?php
                                foreach ($zamenici as $zamenik) {
                                ?>
                                    <option value="<?php echo $zamenik['JAVNASIFRA']; ?>" data-zamenik="<?php echo $zamenik['SIFRA']; ?>"><?php echo $zamenik['NAZIV'] ?></option>
                                <?php
                                }
                                ?>
                            </select>
                            <label for="razlog[]" class="error" style="display: none">Izaberite</label>
                        <?php } else { ?>
                            <div><?php echo $_SESSION["ime"]; ?><input type="text" value="<?php echo $_SESSION["javna"]; ?>" name="menadzer" hidden></div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="objekat">Objekat:</label>
                        <select class="form-control" id="objekat" name="objekat">
                            <option value="999999999">-- Svi --</option>
                            <?php
                            foreach ($skladista as $skladiste) {
                                echo "<option value='" . $skladiste["id"] . "'>" . $skladiste["id"] . " - " . $skladiste["NAZIV"] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Razlog posete:</label>
                        <select class="form-control" id="razlog" name="razlog">
                            <option value="999999999">-- Svi --</option>
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
                <?php if ($_SESSION["ovlascenje"] == 2) { ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Izvršio:</label>
                            <select class="form-control" id="izvrsilac" name="izvrsilac">
                                <option value="">-- Svi --</option>
                                <option value="zamenici">Zamenici</option>
                                <option value="vlastiti">Ja</option>
                            </select>
                            <label for="izvrsilac" class="error" style="display: none">Izaberite</label>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="">Ocena opšteg izlaganja robe u MPO:</label>
                        <div class="d-flex ocene-container">
                            <label for="ocena_izlaganja_od">Od: <input type="number" name="ocena_izlaganja_od" id="ocena_izlaganja_od" step=".01"></label>
                            <label for="ocena_izlaganja_do">Do: <input type="number" name="ocena_izlaganja_do" id="ocena_izlaganja_do" disabled step=".01"></label>
                            <div id="error-ocena_izlaganja"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ocena_izgleda">Ocena opšteg izgleda i higijene MPO:</label>
                        <div class="d-flex ocene-container">
                            <label for="ocena_izgleda_od">Od: <input type="number" name="ocena_izgleda_od" id="ocena_izgleda_od" step=".01"></label>
                            <label for="ocena_izgleda_do" disabled>Do: <input type="number" name="ocena_izgleda_do" id="ocena_izgleda_do" disabled step=".01"></label>
                            <div id="error-ocena_izgleda"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="ocena_mpo">Ocena MPO:</label>
                        <div class="d-flex ocene-container">
                            <label for="ocena_mpo_od">Od: <input type="number" name="ocena_mpo_od" id="ocena_mpo_od" step=".01"></label>
                            <label for="ocena_mpo_do">Do: <input type="number" name="ocena_mpo_do" id="ocena_mpo_do" disabled step=".01"></label>
                            <div id="error-ocena_mpo"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-inline-flex">
                    <div class="form-group">
                        <label for="datum">Vremenski period:</label>
                        <div class="d-flex">
                            <label for="datum" class="mt-0"><input class="form-control" class="form-control" type="text" name="datum" id="datum"></label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <a href="logout.php" class="btn btn-danger btn-lg mt-4">Izloguj se</a>
                    <a href="/izvestaji/" class="btn btn-info btn-lg mt-4">Nazad na izbor</a>
                    <input class="btn btn-success float-right btn-lg mt-4  mb-5" type="submit" value="Prikaži" id="submit" name="submit">
                </div>
            </div>
        </form>
    </div>
    <div class="container">
        <div id="exportPDF"></div>
    </div>
    <div class="container">
        <div id="tabela"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

    <script>
        function downloadExcel() {
            var form = $("#izvestaj");
            var url = "promet-po-objektima-export.php";
            // e.preventDefault();
            $.ajax({
                type: "POST",
                url: url,
                data: form.serialize(),
                success: function(data) {
                    $('#exportPDF').html("");
                    $('<iframe>', {
                        src: 'promet-po-objektima-export.php?' + form.serialize(),
                        frameborder: 0,
                        scrolling: 'no'
                    }).appendTo('#exportPDF');
                    // window.open('promet-po-objektima-export.php?' + form.serialize());
                }
            });

        };
        $(document).ready(function() {

            $('input[name="datum"]').daterangepicker({
                autoUpdateInput: false,
                maxDate: moment(),
                locale: {
                    "cancelLabel": 'Očisti',
                    "applyLabel": "Primeni",
                    "fromLabel": "Od",
                    "toLabel": "Do",
                    "daysOfWeek": [
                        "Ne",
                        "Po",
                        "Ut",
                        "Sr",
                        "Če",
                        "Pe",
                        "Su"
                    ],
                    "monthNames": [
                        "Januar",
                        "Februar",
                        "Mart",
                        "April",
                        "Maj",
                        "Jun",
                        "Jul",
                        "Augvst",
                        "Septembar",
                        "Oktobar",
                        "Novembar",
                        "Decembar"
                    ]
                }
            });

            $('input[name="datum"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' - ' + picker.endDate.format('DD-MM-YYYY'));
            });

            $('input[name="datum"]').on('cancel.daterangepicker', function(ev, picker) {
                //do something, like clearing an input
                $('input[name="datum"]').val('');
            });

            $('#ocena_izlaganja_od').on('keyup', function() {
                if ($(this).val() != '') {
                    $('#ocena_izlaganja_do').prop('disabled', false);
                } else {
                    $('#ocena_izlaganja_do').prop('disabled', true);
                    $('#ocena_izlaganja_do').val("");
                }
            });

            $('#ocena_izgleda_od').on('keyup', function() {
                if ($(this).val() != '') {
                    $('#ocena_izgleda_do').prop('disabled', false);
                } else {
                    $('#ocena_izgleda_do').prop('disabled', true);
                    $('#ocena_izgleda_do').val("");
                }
            });

            $('#ocena_mpo_od').on('keyup', function() {
                if ($(this).val() != '') {
                    $('#ocena_mpo_do').prop('disabled', false);
                } else {
                    $('#ocena_mpo_do').prop('disabled', true);
                    $('#ocena_mpo_do').val("");
                }
            });


            $("#menadzer").change(function() {
                if($("#zamenik").length != 0) {
                    $("#zamenik").val("999999999");
                }
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
            
            $("#zamenik").change(function() {
                $("#menadzer").val("999999999");
                $("#objekat").val("999999999");
                var data = {
                    zamenik: $('#zamenik').find('option:selected').data('zamenik'),
                }
                $.ajax({
                    type: "POST",
                    url: "zamenici-select.php",
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

                        $('#tabela-prva').DataTable({
                            "ordering": false,
                            dom: 'Blfrtip',

                            buttons: [{
                                text: 'Excel',
                                action: function(e, dt, node, config) {
                                    downloadExcel();
                                }
                            }],

                            customize: function(doc) {
                                //pageMargins [left, top, right, bottom]
                                doc.pageMargins = [50, 20, 50, 20];
                            },

                            "pageLength": 50,
                            "lengthMenu": [
                                [50, 200, 500, -1],
                                [50, 200, 500, "Sve"]
                            ],

                            "language": {
                                "sProcessing": "Procesiranje u toku...",
                                "sLengthMenu": "Prikaži _MENU_ elemenata",
                                "sZeroRecords": "Nije pronađen nijedan rezultat",
                                "sInfo": "Prikaz _START_ do _END_ od ukupno _TOTAL_ elemenata",
                                "sInfoEmpty": "Prikaz 0 do 0 od ukupno 0 elemenata",
                                "sInfoFiltered": "(filtrirano od ukupno _MAX_ elemenata)",
                                "sInfoPostFix": "",
                                "sSearch": "Pretraga:",
                                "sUrl": "",
                                "oPaginate": {
                                    "sFirst": "Početna",
                                    "sPrevious": "Prethodna",
                                    "sNext": "Sledeća",
                                    "sLast": "Poslednja"
                                }
                            }

                        });

                    }
                });

            });
        });
    </script>

</body>

</html>