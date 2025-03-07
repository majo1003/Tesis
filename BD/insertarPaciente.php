<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['id_paciente'])) {
    die("Error: ID del paciente no encontrado en la sesión.");
}

$id_paciente = $_SESSION['id_paciente'];
$id_doctor = $_POST['doctor'];
$fecha_hora = $_POST['fecha'];
$id_horario = $_POST['hora'];
$tipo_enfermedad = $_POST['tipo_enfermedad'];
$descripcion = $_POST['descripcion']; // Capturar la descripción

$query = "INSERT INTO cita (fecha_hora, descripcion, atendida, id_paciente, id_doctor, id_horario, tipo_enfermedad) 
          VALUES (?, ?, 0, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("ssiiss", $fecha_hora, $descripcion, $id_paciente, $id_doctor, $id_horario, $tipo_enfermedad);

if ($stmt->execute()) {
    echo "Cita reservada con éxito.";
} else {
    echo "Error al reservar la cita: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
