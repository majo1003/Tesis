<?php
header('Content-Type: application/json');
include 'conexion.php';

if (isset($_POST['id_cita'], $_POST['atendida'])) {
    $id_cita = intval($_POST['id_cita']);
    $atendida = intval($_POST['atendida']);

    $stmt = $conn->prepare("UPDATE cita SET atendida = ? WHERE id_cita = ?");
    $stmt->bind_param("ii", $atendida, $id_cita);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
}

$conn->close();
?>
