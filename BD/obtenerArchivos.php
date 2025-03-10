<?php
header('Content-Type: application/json; charset=utf-8');
include 'conexion.php';

if (!isset($_POST['idCita'])) {
    echo json_encode(["error" => "No existen archivos"]);
    exit;
}

$idCita = $_POST['idCita'];

$sql = "SELECT id_archivo, tipo FROM archivo WHERE id_cita = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idCita);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response[] = [
            "id_archivo" => $row["id_archivo"],
            "nombre_archivo" => $row["tipo"]
        ];
    }
} else {
    $response = ["mensaje" => "⚠️ No hay archivos disponibles."];
}

$stmt->close();
$conn->close();

echo json_encode($response, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
