<?php
include 'conexion.php';
session_start();

// Verificar sesión de doctor
if (!isset($_SESSION['id_doctor'])) {
    echo json_encode(['error' => 'Sesión no iniciada.']);
    exit;
}

$id_doctor = $_SESSION['id_doctor'];
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

// Construir y preparar la consulta SQL
if ($foto !== null) {
    $sql = "UPDATE doctor SET nombre = ?, apellido = ?, edad = ?, ubicacion = ?, foto = ? WHERE id_doctor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissi", $nombre, $apellido, $edad, $ubicacion, $foto, $id_doctor);
} else {
    $sql = "UPDATE doctor SET nombre = ?, apellido = ?, edad = ?, ubicacion = ? WHERE id_doctor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisi", $nombre, $apellido, $edad, $ubicacion, $id_doctor);
}

// Ejecutar y responder
if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Perfil actualizado con éxito.']);
} else {
    echo json_encode(['error' => 'Error al actualizar el perfil: ' . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
