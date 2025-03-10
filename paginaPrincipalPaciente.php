<?php
include 'BD/conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un paciente
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'paciente') {
    header("Location: index.php");
    exit;
}

// Verificar si existe el ID del paciente en la sesión
if (!isset($_SESSION['id_paciente'])) {
    die("Error: ID del paciente no encontrado en la sesión.");
}

$id_paciente = $_SESSION['id_paciente'];

// Obtener el nombre del paciente
$queryPaciente = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo FROM paciente WHERE id_paciente = ?";
$stmtPaciente = $conn->prepare($queryPaciente);
$stmtPaciente->bind_param("i", $id_paciente);
$stmtPaciente->execute();
$resultPaciente = $stmtPaciente->get_result();
$nombrePaciente = "Paciente"; // Valor por defecto

if ($row = $resultPaciente->fetch_assoc()) {
    $nombrePaciente = $row['nombre_completo'];
}

// Consultar las citas del paciente
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

// Obtener los doctores disponibles
$sqlDoctores = "SELECT id_doctor, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM doctor";
$resultDoctores = $conn->query($sqlDoctores);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/stylesPaciente.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Mis Citas</title>
</head>
<body>

<header class="cabezera-usuario">
    <div class="titulo">
    <h1>Bienvenido <?php echo htmlspecialchars($nombrePaciente); ?></h1>
    </div>

    <div class="cerrar-sesion">
    <a href="BD/cerrarSesion.php" class="dr-buttonHeader">Cerrar Sesión</a>
    </div>
</header>

<main class="usuario-principal">
    <section id="reservaciones">
        <div class="lista-paciente">
            <h1>Tus reservaciones</h1>
            <button id="abrirModal" class="dr-button">Reservar Cita</button>
        </div>

        <div class="tabla-pacientes dr-padding-100px-lados">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Doctor</th>
                        <th>Problema</th>
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
                            <td>
                                <?= date("d/m/Y", strtotime($row['fecha_hora'])) ?> 
                                <?= date("H:i", strtotime($row['hora_inicio'])) ?> - <?= date("H:i", strtotime($row['hora_fin'])) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </section>

<!-- Modal -->
<div id="modalReserva" class="modal">
    <div class="modal-content">
        <span class="close" id="cerrarModal">&times;</span>
        <h2 class="modal-title">Reservar Cita</h2>
        <form id="formReserva" action="BD/guardarCita.php" method="POST">
            <div class="modal-body">

                <!-- Campo oculto para el paciente -->
                <div id="paciente" data-id="<?= $_SESSION['id_paciente']; ?>"></div>
                <div class="modal-reserva-container">

                <label for="doctor">Seleccionar Doctor:</label>
                    <select id="doctor" name="doctor" required>
                        <option value="">Seleccione un doctor</option>
                        <?php while ($doctor = $resultDoctores->fetch_assoc()): ?>
                            <option value="<?= htmlspecialchars($doctor['id_doctor']) ?>">
                                <?= htmlspecialchars($doctor['nombre_completo']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <div class="detalle-elemento">
                        <label for="tipo_enfermedad">Tipo de Enfermedad:</label>
                        <input type="text" id="tipo_enfermedad" name="tipo_enfermedad" required>
                    </div>

                    <div class="detalle-descripcion">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>

                </div>

                <div class="calendario">
                    <label for="fecha">Seleccionar Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>

                <div class="horas-disponibles">
                    <label>Horas disponibles:</label>
                    <ul id="lista-horas">
                        <!-- Las horas se cargarán dinámicamente con AJAX -->
                    </ul>
                    <input type="hidden" id="horaSeleccionada" name="horaSeleccionada">
                </div>
            </div>

            <div class="modal-footer">
            <button class="btn btn-confirmar" type="submit">Reservar</button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function () {
    // Asegura que el modal esté oculto al cargar la página
    $("#modalReserva").hide();  

    const fechaInput = $("#fecha");
    let hoy = new Date().toISOString().split("T")[0];  // Fecha mínima es hoy
    fechaInput.attr("min", hoy);  // Establece la fecha mínima

    // Funciones para abrir y cerrar el modal
    $("#abrirModal").click(function () {
        $("#modalReserva").fadeIn();
    });

    $("#cerrarModal").click(function () {
        $("#modalReserva").fadeOut();
    });

    $(window).click(function (event) {
        if (event.target.id === "modalReserva") {
            $("#modalReserva").fadeOut();
        }
    });

    // Cargar horas disponibles
    $("#doctor, #fecha").change(function () {
        var doctor = $("#doctor").val();
        var fecha = $("#fecha").val();

        if (doctor && fecha) {
            $.ajax({
                url: "BD/obtenerHoras.php",
                type: "POST",
                data: { doctor: doctor, fecha: fecha },  // Se pasa la fecha como 'DATE' (YYYY-MM-DD)
                success: function (response) {
                    $("#lista-horas").html(response);
                },
                error: function () {
                    alert("Error al cargar las horas disponibles.");
                }
            });
        } else {
            $("#lista-horas").html(""); // Si no se selecciona doctor o fecha, vacía la lista
        }
    });

    // Manejo de la selección de la hora
    $(document).on("click", ".hora-btn", function () {
        $(".hora-btn").removeClass("hora-seleccionada");
        $(this).addClass("hora-seleccionada");

        var horaSeleccionada = $(this).data("id");  
        $("#horaSeleccionada").val(horaSeleccionada);  
        console.log("Hora seleccionada:", horaSeleccionada);  
    });

    // Enviar formulario de reserva
    $("#formReserva").submit(function (event) {
        event.preventDefault();
        
        var doctor = $("#doctor").val();
        var fecha = $("#fecha").val();
        var paciente = $("#paciente").data("id");
        var horaSeleccionada = $("#horaSeleccionada").val();  
        var tipoEnfermedad = $("#tipo_enfermedad").val();
        var descripcion = $("#descripcion").val();

        if (!doctor || !fecha || !horaSeleccionada || !paciente || !tipoEnfermedad || !descripcion) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        console.log("Datos enviados:", {
            doctor: doctor,
            fecha: fecha,
            hora: horaSeleccionada,  
            paciente: paciente,
            tipo_enfermedad: tipoEnfermedad,
            descripcion: descripcion
        });

        $.ajax({
            url: "BD/guardarCita.php",
            type: "POST",
            data: {
                doctor: doctor,
                fecha: fecha,
                hora: horaSeleccionada,  
                tipo_enfermedad: tipoEnfermedad,
                descripcion: descripcion,
                paciente: paciente
            },
            success: function (response) {
                alert(response);
                $("#formReserva")[0].reset();
                $("#modalReserva").fadeOut();

                // Actualizar la tabla de citas
                $.ajax({
                    url: "BD/obtenerCitas.php",
                    type: "POST",
                    success: function (data) {
                        $("tbody").html(data); 
                    },
                    error: function () {
                        alert("Error al actualizar las citas.");
                    }
                });
            },
            error: function () {
                alert("Error al reservar la cita.");
            }
        });
    });
});
</script>




</body>
</html>
