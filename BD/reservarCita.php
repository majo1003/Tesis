<?php
session_start();

// Verificar si el usuario está autenticado como paciente
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'paciente') {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

$horaSeleccionada = isset($_POST['horaSeleccionada']) ? $_POST['horaSeleccionada'] : null;
$descripcion = isset($_POST['descripcion']) ? $_POST['descripcion'] : null;

// Validar los parámetros
if (!$horaSeleccionada || !$descripcion) {
    echo json_encode(["error" => "Parámetros inválidos"]);
    exit;
}

// Conectar a la base de datos
$host = 'localhost'; // Cambia si es necesario
$db = 'ClinicaDB';
$user = 'root'; // Cambia si es necesario
$pass = ''; // Cambia si es necesario

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}

// Obtener el id del paciente desde la sesión
$id_paciente = $_SESSION['id_paciente']; 

// Insertar la cita
$stmt = $pdo->prepare('
    INSERT INTO Cita (fecha_hora, descripcion, id_paciente, id_doctor, id_horario)
    VALUES (NOW(), :descripcion, :id_paciente, :id_doctor, :horaSeleccionada)
');
$stmt->execute(['descripcion' => $descripcion, 'id_paciente' => $id_paciente, 'id_doctor' => 1, 'horaSeleccionada' => $horaSeleccionada]);

// Devolver éxito
echo json_encode(["success" => true]);
?>
