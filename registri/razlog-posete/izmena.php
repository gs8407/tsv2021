<?php
require_once '../../config.php';

$id = $_REQUEST["id"];
$tip = $_REQUEST["tip"];

echo $id ." ". $tip;

$sql = "UPDATE razlog_posete SET status = '$tip' WHERE id = '$id'";

if ($conn->query($sql) === TRUE) {
    echo "Record updated successfully";
} else {
    echo "Error updating record: " . $conn->error;
}
$conn->close();