<?php
session_start();
header('Content-Type: application/json');

// Asegurarnos de que el paciente esté autenticado
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'paciente') {
    echo json_encode(["error" => "Usuario no autenticado"]);
    exit;
}

// Obtener los parámetros de la URL
$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : null;
$id_doctor = isset($_GET['id_doctor']) ? $_GET['id_doctor'] : null;

// Validar los parámetros
if (!$fecha || !$id_doctor) {
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

// Consultar las horas disponibles
$stmt = $pdo->prepare('
    SELECT h.id_hora, h.hora_inicio, h.hora_fin, hd.estado
    FROM Horas h
    JOIN HorarioDisponibilidad hd ON h.id_hora = hd.id_hora
    WHERE hd.id_doctor = :id_doctor AND hd.fecha = :fecha
');
$stmt->execute(['id_doctor' => $id_doctor, 'fecha' => $fecha]);
$horasDisponibles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Retornar las horas en formato JSON
echo json_encode($horasDisponibles);
?>
