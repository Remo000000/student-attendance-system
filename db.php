<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "attendance_system";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Қосылу қатесі: " . mysqli_connect_error());
}
?>
