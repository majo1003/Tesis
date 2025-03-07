
<?php
include 'BD/conexion.php';
session_start();

if (!isset($_SESSION['id_paciente'])) {
    die("Error: Sesión no iniciada.");
}

$id_paciente = $_SESSION['id_paciente'];
$query = "SELECT c.fecha_hora, d.nombre AS doctor, c.tipo_enfermedad, c.descripcion 
          FROM Cita c 
          JOIN Doctor d ON c.id_doctor = d.id_doctor 
          WHERE c.id_paciente = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_paciente);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Citas</title>
</head>
<body>

    <h2>Mis Citas</h2>
    
    <table border="1">
        <thead>
            <tr>
                <th>Doctor</th>
                <th>Tipo de Enfermedad</th>
                <th>Descripción</th>
                <th>Fecha y Hora</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['doctor']) ?></td>
                    <td><?= htmlspecialchars($row['tipo_enfermedad']) ?></td>
                    <td><?= htmlspecialchars($row['descripcion']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($row['fecha_hora'])) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>
