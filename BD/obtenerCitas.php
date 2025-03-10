<?php
include 'conexion.php';
session_start();

if (!isset($_SESSION['id_paciente'])) {
    die("Error: ID del paciente no encontrado en la sesión.");
}

$id_paciente = $_SESSION['id_paciente'];

$query = "SELECT c.descripcion, c.fecha_hora, c.tipo_enfermedad, d.nombre AS doctor, h.hora_inicio, h.hora_fin
          FROM cita c
          JOIN doctor d ON c.id_doctor = d.id_doctor
          JOIN horariodisponibilidad hd ON c.id_horario = hd.id_horario
          JOIN horas h ON hd.id_hora = h.id_hora
          WHERE c.id_paciente = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>{$row['doctor']}</td>
            <td>{$row['descripcion']}</td>
            <td>{$row['tipo_enfermedad']}</td>
            <td>
                {$row['fecha_hora']}
                {$row['hora_inicio']} - {$row['hora_fin']}
            </td>
          </tr>";
}
?>
