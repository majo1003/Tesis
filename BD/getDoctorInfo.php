<?php
// Activar la depuración de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include('conexion.php');

// Verificar si el parámetro ID del paciente está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID del doctor no proporcionado']);
    exit();
}

// Obtener el ID del paciente desde la URL
$id_paciente = intval($_GET['id']);

// Consulta SQL para obtener datos del paciente
$sql = "SELECT nombre, apellido, edad, ubicacion, foto FROM doctor WHERE id_doctor = ? LIMIT 1";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id_paciente);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nombre, $apellido, $edad, $ubicacion, $foto);
        $stmt->fetch();

        // Convertir la foto a base64 si existe
        $foto_base64 = $foto ? base64_encode($foto) : null;

        // Construir respuesta
        $respuesta = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'edad' => $edad,
            'ubicacion' => $ubicacion,
            'foto' => $foto_base64
        ];

        echo json_encode($respuesta);
    } else {
        echo json_encode(['error' => 'Doctor no encontrado']);
    }

    $stmt->close();
} else {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
}

$conn->close();
?>
