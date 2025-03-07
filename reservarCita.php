<?php
include 'BD/conexion.php'; // Conectar a la base de datos

// Obtener los doctores disponibles
$sqlDoctores = "SELECT id_doctor, CONCAT(nombre, ' ', apellido) AS nombre_completo FROM Doctor";
$resultDoctores = $conn->query($sqlDoctores);

// Obtener las horas disponibles (se cargarán dinámicamente según el doctor y fecha)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserva de Cita</title>
    <link rel="stylesheet" href="css/styles-paciente.css">
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function () {
    const fechaInput = $("#fecha");
    let hoy = new Date().toISOString().split("T")[0];
    fechaInput.attr("min", hoy);

    // Cuando cambia la selección del doctor o la fecha
    $("#doctor, #fecha").change(function () {
        var doctor = $("#doctor").val();
        var fecha = $("#fecha").val();

        if (doctor && fecha) {
            $.ajax({
                url: "BD/obtenerHoras.php",
                type: "POST",
                data: { doctor: doctor, fecha: fecha },
                success: function (response) {
                    $("#lista-horas").html(response);
                },
                error: function () {
                    alert("Error al cargar las horas disponibles.");
                }
            });
        } else {
            $("#lista-horas").html("");
        }
    });

    // Manejo de la selección de la hora
    $(document).on("click", ".hora-btn", function () {  // Aseguramos que los botones dinámicos también sean capturados
        $(".hora-btn").removeClass("hora-seleccionada");
        $(this).addClass("hora-seleccionada");

        var horaSeleccionada = $(this).data("id");
        $("#horaSeleccionada").val(horaSeleccionada); // Asignar el valor de la hora seleccionada
        console.log("Hora seleccionada:", horaSeleccionada);
    });

    // Envío del formulario
    $("#formReserva").submit(function (event) {
        event.preventDefault();
        
        var doctor = $("#doctor").val();
        var fecha = $("#fecha").val();
        var hora = $("#horaSeleccionada").val(); // Obtener la hora seleccionada

        // Validar que se haya seleccionado una hora
        if (!hora) {
            alert("Por favor, seleccione una hora.");
            return;
        }

        var tipoEnfermedad = $("#tipo_enfermedad").val();
        var descripcion = $("#descripcion").val();  // Obtener la descripción

        // Realizar la petición AJAX para enviar todos los datos
        $.ajax({
            url: "BD/guardarCita.php",
            type: "POST",
            data: {
                doctor: doctor,
                fecha: fecha,
                hora: hora,
                tipo_enfermedad: tipoEnfermedad,
                descripcion: descripcion  // Incluir descripción en los datos enviados
            },
            success: function (response) {
                alert(response);
                $("#formReserva")[0].reset();  // Resetear el formulario
            },
            error: function () {
                alert("Error al reservar la cita.");
            }
        });
    });
});
</script>
</head>
<body>

<div id="modalReserva" class="modal">
    <div class="modal-contenido">
        <!-- Encabezado del Modal -->
        <div class="modal-header">
            <h2 class="modal-title">Reserva de Cita</h2>
            <span class="close" onclick="cerrarModal('modalReserva')">&times;</span>
        </div>

        <!-- Contenido del Modal -->
        <div class="modal-body">
            <form id="formReserva" action="BD/guardarCita.php" method="POST">
                <!-- Campo oculto para ID del paciente -->
                <input type="hidden" name="paciente" value="<?= $id_paciente ?>">
                <div class="modal-reserva-container">
                    <!-- Selección de Tipo de Enfermedad -->
                    <div class="detalle-elemento">
                        <label for="tipo_enfermedad" class="dr-bold">Tipo de Enfermedad:</label>
                        <input type="text" id="tipo_enfermedad" name="tipo_enfermedad" required>
                    </div>

                    <div class="detalle-descripcion">
                        <label for="descripcion" class="dr-bold">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>
                                    <!-- Selección de Doctor -->
                    <div>
                        <label for="doctor" class="dr-bold">Seleccionar Doctor:</label>
                        <select id="doctor" name="doctor" required>
                            <option value="">Seleccione un doctor</option>
                            <?php while ($doctor = $resultDoctores->fetch_assoc()): ?>
                                <option value="<?= htmlspecialchars($doctor['id_doctor']) ?>">
                                    <?= htmlspecialchars($doctor['nombre_completo']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                

                <!-- Selección de Fecha -->
                <div class="detalle-elemento calendario">
                    <label for="fecha" class="dr-bold">Seleccionar Fecha:</label>
                    <input type="date" id="fecha" name="fecha" required>
                </div>

                <!-- Selección de Horas -->
                <div>
                    <label>Horas disponibles:</label>
                    <ul id="lista-horas">
                        <!-- Las horas se cargarán dinámicamente con AJAX -->
                    </ul>
                    <input type="hidden" id="horaSeleccionada" name="horaSeleccionada">
                </div>

                <!-- Botón de Reserva -->
                <button type="submit" class="btn-reservar">Reservar</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
