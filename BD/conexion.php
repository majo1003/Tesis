<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clinicadb";
$port = 3307;

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname, $port);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
// Ya no se necesita este mensaje, eliminamos esta respuesta.
?>
