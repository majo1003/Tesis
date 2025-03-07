<?php
$servername = "beismr0pb0ihjsbpmtcq-mysql.services.clever-cloud.com";
$username = "ux0buns4jfll99dx";
$password = "ah5zdfyzilWtCv1O98Qw";
$dbname = "beismr0pb0ihjsbpmtcq";
$port = 3306;

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Ya no se necesita este mensaje, eliminamos esta respuesta.
?>
