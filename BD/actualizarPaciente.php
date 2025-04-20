<?php
include 'conexion.php';
session_start();

// Verificar sesión de paciente
if (!isset($_SESSION['id_paciente'])) {
    echo json_encode(['error' => 'Sesión no iniciada.']);
    exit;
}

$id_paciente = $_SESSION['id_paciente'];
$nombre = $_POST['nombre'] ?? null;
$apellido = $_POST['apellido'] ?? null;
$edad = $_POST['edad'] ?? null;
$ubicacion = $_POST['ubicacion'] ?? null;

// Validaciones básicas
if (!$nombre || !$apellido || !$edad || !$ubicacion) {
    echo json_encode(['error' => 'Faltan datos obligatorios.']);
    exit;
}

// Procesar foto si se subió
$foto = null;
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $foto = file_get_contents($_FILES['foto']['tmp_name']);
}

// Construir y ejecutar la consulta SQL dinámica
if ($foto !== null) {
    $sql = "UPDATE paciente SET nombre = ?, apellido = ?, edad = ?, ubicacion = ?, foto = ? WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $nombre, $apellido, $edad, $ubicacion, $foto, $id_paciente);
} else {
    $sql = "UPDATE paciente SET nombre = ?, apellido = ?, edad = ?, ubicacion = ? WHERE id_paciente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $nombre, $apellido, $edad, $ubicacion, $id_paciente);
}

if ($stmt->execute()) {
    header("Location: ../profilePaciente.php");
    exit;
} else {
    echo json_encode(['error' => 'Error al actualizar los datos: ' . $stmt->error]);
    exit;
}
?>
