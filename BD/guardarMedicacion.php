<?php
include 'conexion.php';

// Verificar si se han enviado los parámetros requeridos
if (!isset($_POST['id_paciente']) || !isset($_POST['nombre_medicamento']) || !isset($_POST['dosis']) || !isset($_POST['hora']) || !isset($_POST['dia'])) {
    //echo "❌ Faltan parámetros: id_paciente, nombre_medicamento, dosis, hora";
    echo $_POST['id_paciente'], $_POST['nombre_medicamento'], $_POST['dosis'], $_POST['hora'], $_POST['dia'];
    exit;
}

$id_paciente = $_POST['id_paciente'];
$nombre_medicamento = $_POST['nombre_medicamento'];
$dosis = $_POST['dosis'];  // Cambiado de horaSeleccionada a hora
$hora = $_POST['hora'];
$dia = $_POST['dia'];

echo $_POST['id_paciente'], $_POST['nombre_medicamento'], $_POST['dosis'], $_POST['hora'], $_POST['dia'];

// Verificar que la hora seleccionada existe en la tabla Horas
// $queryHora = "SELECT id_hora FROM horas WHERE id_hora = ?";
// $stmtHora = $conn->prepare($queryHora);
// $stmtHora->bind_param("i", $hora);  // Usar la variable hora
// $stmtHora->execute();
// $resultadoHora = $stmtHora->get_result();

// Si no existe la hora seleccionada, mostrar error
// if ($resultadoHora->num_rows == 0) {
//     echo "❌ El horario seleccionado no es válido.";
//     exit;
// }

// Verificar disponibilidad del doctor para la fecha y hora seleccionada
// $queryDisponibilidad = "SELECT id_horario 
//                         FROM horariodisponibilidad 
//                         WHERE id_doctor = ? AND fecha = ? AND id_hora = ? AND estado = 1";
// $stmtDisponibilidad = $conn->prepare($queryDisponibilidad);
// $stmtDisponibilidad->bind_param("isi", $doctor, $fecha, $hora);  // Se vinculan los parámetros dinámicos
// $stmtDisponibilidad->execute();
// $resultadoDisponibilidad = $stmtDisponibilidad->get_result();

// Si no hay disponibilidad, mostrar error
// if ($resultadoDisponibilidad->num_rows == 0) {
//     echo "❌ El doctor no tiene disponibilidad para la fecha y hora seleccionada.";
//     exit;
// }

// Obtener el id_horario de la tabla HorarioDisponibilidad
// $row = $resultadoDisponibilidad->fetch_assoc();
// $id_horario = $row['id_horario'];

// Construir la fecha y hora en el formato DATETIME (ejemplo: '2025-03-07 14:00:00')
// $hora_format = $hora . ':00:00';
$id_paciente_5 = 5;
//$id = 3;

// Aquí puedes realizar la inserción de la cita, por ejemplo:
#######
$queryMedicamento = "INSERT INTO medicamentos (id_paciente, nombre_medicamento, dosis, hora, dia) 
              VALUES (?, ?, ?, ?, ?)";
$stmtMedicamento = $conn->prepare($queryMedicamento);
$stmtMedicamento->bind_param("issss", $id_paciente, $nombre_medicamento, $dosis, $hora, $dia);
$stmtMedicamento->execute();

// Actualizar el estado del horario en la tabla horariodisponibilidad a 0 (no disponible)
// $queryActualizarDisponibilidad = "UPDATE horariodisponibilidad SET estado = 0 WHERE id_horario = ?";
// $stmtActualizarDisponibilidad = $conn->prepare($queryActualizarDisponibilidad);
// $stmtActualizarDisponibilidad->bind_param("i", $id_horario);
// $stmtActualizarDisponibilidad->execute();

// Confirmación de que la cita fue registrada correctamente
echo "✔️ Cita registrada con éxito.".$id_paciente.$nombre_medicamento.$dosis.$hora;

// Cerrar las conexiones
$stmtMedicamento->close();
// $stmtDisponibilidad->close();
// $stmtCita->close();
// $stmtActualizarDisponibilidad->close();
// $conn->close();
?>
