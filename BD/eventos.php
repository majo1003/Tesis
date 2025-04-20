<?php
session_start();
require 'conexion.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id_doctor'])) {
    error_log("No hay sesión activa del doctor");
    echo json_encode([]);
    exit;
}

$idDoctor = $_SESSION['id_doctor'];
error_log("Buscando citas para el doctor con ID: " . $idDoctor);

$sql = "SELECT id_cita, fecha_hora, descripcion, tipo_enfermedad, atendida FROM cita WHERE id_doctor = ?";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    error_log("Error en prepare: " . $conn->error);
    echo json_encode([]);
    exit;
}

$stmt->bind_param("i", $idDoctor);
$stmt->execute();
$result = $stmt->get_result();

$eventos = [];

while ($row = $result->fetch_assoc()) {
    $eventos[] = [
        'id' => $row['id_cita'],
        'title' => $row['descripcion'],
        'start' => $row['fecha_hora'],
        'color' => $row['atendida'] ? '#28a745' : '#dc3545',
        'extendedProps' => [
            'tipo_enfermedad' => $row['tipo_enfermedad'],
            'atendida' => $row['atendida']
        ]
    ];
}

error_log("Eventos generados: " . json_encode($eventos));
echo json_encode($eventos);
?>
