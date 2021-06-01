<?php
require_once '../../config.php';
$naziv = $_REQUEST["naziv"];

$sql = "INSERT INTO razlog_posete (naziv, status) VALUES ('$naziv', 1)";

if ($conn->query($sql) === TRUE) {
    $last_id = $conn->insert_id;
    echo $last_id;
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();