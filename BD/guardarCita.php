<?php
include 'conexion.php';

// Verificar si se han enviado los parámetros requeridos
if (!isset($_POST['doctor']) || !isset($_POST['fecha']) || !isset($_POST['hora']) || !isset($_POST['paciente']) || !isset($_POST['tipo_enfermedad']) || !isset($_POST['descripcion'])) {
    echo "❌ Faltan parámetros: doctor, fecha, hora, paciente, tipo de enfermedad o descripción";
    exit;
}

$doctor = $_POST['doctor'];
$fecha = $_POST['fecha'];
$hora = $_POST['hora'];  // Cambiado de horaSeleccionada a hora
$paciente = $_POST['paciente'];
$tipo_enfermedad = $_POST['tipo_enfermedad'];
$descripcion = $_POST['descripcion'];

// Verificar que la hora seleccionada existe en la tabla Horas
$queryHora = "SELECT id_hora FROM horas WHERE id_hora = ?";
$stmtHora = $conn->prepare($queryHora);
$stmtHora->bind_param("i", $hora);  // Usar la variable hora
$stmtHora->execute();
$resultadoHora = $stmtHora->get_result();

// Si no existe la hora seleccionada, mostrar error
if ($resultadoHora->num_rows == 0) {
    echo "❌ El horario seleccionado no es válido.";
    exit;
}

// Verificar disponibilidad del doctor para la fecha y hora seleccionada
$queryDisponibilidad = "SELECT id_horario 
                        FROM horariodisponibilidad 
                        WHERE id_doctor = ? AND fecha = ? AND id_hora = ? AND estado = 1";
$stmtDisponibilidad = $conn->prepare($queryDisponibilidad);
$stmtDisponibilidad->bind_param("isi", $doctor, $fecha, $hora);  // Se vinculan los parámetros dinámicos
$stmtDisponibilidad->execute();
$resultadoDisponibilidad = $stmtDisponibilidad->get_result();

// Si no hay disponibilidad, mostrar error
if ($resultadoDisponibilidad->num_rows == 0) {
    echo "❌ El doctor no tiene disponibilidad para la fecha y hora seleccionada.";
    exit;
}

// Obtener el id_horario de la tabla HorarioDisponibilidad
$row = $resultadoDisponibilidad->fetch_assoc();
$id_horario = $row['id_horario'];

// Construir la fecha y hora en el formato DATETIME (ejemplo: '2025-03-07 14:00:00')
$fecha_hora = $fecha . ' ' . $hora . ':00:00';

// Aquí puedes realizar la inserción de la cita, por ejemplo:
$queryCita = "INSERT INTO cita (fecha_hora, id_doctor, id_horario, id_paciente, tipo_enfermedad, descripcion) 
              VALUES (?, ?, ?, ?, ?, ?)";
$stmtCita = $conn->prepare($queryCita);
$stmtCita->bind_param("sisiss", $fecha_hora, $doctor, $id_horario, $paciente, $tipo_enfermedad, $descripcion);
$stmtCita->execute();

// Actualizar el estado del horario en la tabla horariodisponibilidad a 0 (no disponible)
$queryActualizarDisponibilidad = "UPDATE horariodisponibilidad SET estado = 0 WHERE id_horario = ?";
$stmtActualizarDisponibilidad = $conn->prepare($queryActualizarDisponibilidad);
$stmtActualizarDisponibilidad->bind_param("i", $id_horario);
$stmtActualizarDisponibilidad->execute();

// Confirmación de que la cita fue registrada correctamente
echo "✔️ Cita registrada con éxito.";

// Cerrar las conexiones
$stmtHora->close();
$stmtDisponibilidad->close();
$stmtCita->close();
$stmtActualizarDisponibilidad->close();
$conn->close();
?>
