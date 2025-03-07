<?php
// Activar la depuración de errores
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluir la conexión a la base de datos
include('conexion.php');

// Verificar si el parámetro ID del paciente y el ID de la cita están presentes en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo json_encode(['error' => 'ID del paciente no proporcionado']);
    exit();
}

if (!isset($_GET['id_cita']) || empty($_GET['id_cita'])) {
    echo json_encode(['error' => 'ID de la cita no proporcionado']);
    exit();
}

// Obtener el ID del paciente y el ID de la cita desde la URL
$id_paciente = intval($_GET['id']);
$id_cita = intval($_GET['id_cita']);

// Preparar la consulta SQL con IFNULL para evitar valores NULL en descripcion
$sql = "SELECT 
            p.nombre, 
            p.apellido, 
            IFNULL(c.descripcion, 'Sin descripción') AS descripcion, 
            c.fecha_hora, 
            c.atendida,
            c.tipo_enfermedad, 
            h.hora_inicio, 
            h.hora_fin,
            p.edad,
            p.ubicacion,
            p.foto
        FROM Cita c
        JOIN Paciente p ON c.id_paciente = p.id_paciente
        LEFT JOIN HorarioDisponibilidad hd ON c.id_horario = hd.id_horario
        LEFT JOIN Horas h ON hd.id_hora = h.id_hora
        WHERE p.id_paciente = ? AND c.id_cita = ?
        ORDER BY c.fecha_hora DESC
        LIMIT 1";  // Tomamos solo la cita correspondiente

if ($stmt = $conn->prepare($sql)) {
    // Vincular los parámetros
    $stmt->bind_param("ii", $id_paciente, $id_cita);
    $stmt->execute();
    
    // Vincular los resultados
    $stmt->bind_result($nombre, $apellido, $descripcion, $fecha_hora, $atendida, $tipo_enfermedad, $hora_inicio, $hora_fin, $edad, $ubicacion, $foto);

    // Verificar si hay resultados
    if ($stmt->fetch()) {
        $atendida = ($atendida == 1) ? "Atendida" : "Sin atender";

        // Convertir la foto BLOB a base64 si está presente
        $foto_base64 = null;
        if ($foto) {
            $foto_base64 = base64_encode($foto);  // Convertir el BLOB a base64
        }

        // Construir respuesta
        $respuesta = [
            'nombre' => $nombre . ' ' . $apellido,
            'diagnostico' => $tipo_enfermedad,
            'atendida' => $atendida,
            'descripcion' => $descripcion,
            'hora_inicio' => $hora_inicio,
            'hora_fin' => $hora_fin,
            'edad' => $edad,
            'ubicacion' => $ubicacion,
            'foto' => $foto_base64,  // Foto en base64
        ];

        // Enviar respuesta en formato JSON
        echo json_encode($respuesta);
    } else {
        // Si no se encuentran resultados
        //echo json_encode(['error' => 'No se encontraron citas para este paciente']);
    }

    // Cerrar la consulta
    $stmt->close();
} else {
    echo json_encode(['error' => 'Error en la consulta SQL: ' . $conn->error]);
}

// Cerrar la conexión
$conn->close();
?>
