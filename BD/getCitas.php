<?php
// Activar la depuración de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Conectar a la base de datos
include('conexion.php');

// Verifica si la conexión fue exitosa
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Obtener el ID del doctor desde la sesión
session_start();
if (isset($_SESSION['id_doctor'])) {
    $id_doctor = $_SESSION['id_doctor'];
} else {
    // Si no hay sesión activa, devuelve un error
    echo json_encode(['error' => 'No se ha iniciado sesión']);
    exit();
}

// Consulta para obtener las citas, incluyendo el estado, id_paciente e id_cita
$sql = "SELECT c.id_cita, c.id_paciente, c.descripcion, c.fecha_hora, c.tipo_enfermedad, p.nombre AS paciente, h.hora_inicio, h.hora_fin, c.atendida
        FROM cita c
        JOIN paciente p ON c.id_paciente = p.id_paciente
        JOIN horariodisponibilidad hd ON c.id_horario = hd.id_horario
        JOIN horas h ON hd.id_hora = h.id_hora
        WHERE c.id_doctor = ?";

if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("i", $id_doctor);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica si se encontraron citas
    if ($result->num_rows > 0) {
        $citas = [];
        while ($row = $result->fetch_assoc()) {
            // Modificar el estado: 0 = 'No atendido', 1 = 'Atendido'
            $row['atendida'] = ($row['atendida'] == 0) ? 'No atendido' : 'Atendido';
            $citas[] = $row;
        }
        // Enviar la respuesta como JSON
        echo json_encode($citas);
    } else {
        // Si no hay citas, retorna un array vacío
        echo json_encode([]);
    }
} else {
    // Si la consulta falla, enviar error
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
