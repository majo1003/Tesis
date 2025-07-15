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

//Obtener los medicamentos registrados
$sqlMedicamentos = "SELECT nombre_medicamento, dosis, hora, dia FROM medicamentos WHERE id_paciente = $id_paciente";
$resultMedicamentos = $conn->query($sqlMedicamentos);

$medicamentos = [];

if ($resultMedicamentos && $resultMedicamentos->num_rows > 0) {
    while($row = $resultMedicamentos->fetch_assoc()) {
        $medicamentos[] = $row;
    }
}

// Pasar a JavaScript como JSON
echo "<script>const medicamentosRegistrados = " . json_encode($medicamentos) . "; </script>";    

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
    <style>
    table {
      border-collapse: collapse;
      width: 100%;
      text-align: center;
    }

    td {
      border: 1px solid #999;
      padding: 6px;
      vertical-align: top;
    }

   
    </style>
</head>
<body>

<header class="header-pantalla">
    <div class="titulo">
    <h1>Bienvenido <?php echo htmlspecialchars($nombrePaciente); ?></h1>
    </div>

    <div class="cerrar-sesion">
    <a href="profilePaciente.php" class="dr-buttonHeader">Editar Perfil</a>
    </div>

    <div class="cerrar-sesion">
    <a href="BD/cerrarSesion.php" class="dr-buttonHeader">Cerrar Sesión</a>
    </div>
    
</header>

<main class="usuario-principal">
    <section id="reservaciones">
        <div class="lista-paciente">
            <h1>Tus reservaciones</h1>
            <button id="abrirModalCalendario" class="dr-button" style="min-width: 250px;">Registro de medicamentos</button>
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
            <div class="modal-body" >

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

                    <!-- <div class="detalle-elemento">
                        <label for="tipo_enfermedad">Tipo de Enfermedad:</label>
                        <input type="text" id="tipo_enfermedad" name="tipo_enfermedad" required>
                    </div> -->

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

<!-- Modal Calendario-->
<div id="modalCalendario" class="modal" style="display: none; min-width: 1000px;">
    <div class="modal-content">
        <span class="close" id="cerrarModalCalendario">&times;</span>
        <h2 class="modal-title">Registro de medicamento</h2>
        <form id="formReservaCalendario" action="BD/guardarMedicacion.php" method="POST">

            <div class="modal-body" style="display: flex;">                

                <!-- Campo oculto para el paciente -->
                <div id="id_paciente" data-id="<?= $_SESSION['id_paciente']; ?>"></div>
                <div class="modal-reserva-container">

                <!-- <label for="doctor">Seleccionar Doctor:</label>
                    <select id="doctor" name="doctor" required>
                        <option value="">Seleccione un doctor</option>
                        
                    </select>

                    <div class="detalle-elemento">
                        <label for="tipo_enfermedad">Tipo de Enfermedad:</label>
                        <input type="text" id="tipo_enfermedad" name="tipo_enfermedad" required>
                    </div>

                    <div class="detalle-descripcion">
                        <label for="descripcion">Descripción:</label>
                        <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
                    </div>

                </div> -->

                <h2 style="text-align: center;">Tabla de Horas - Semana Actual</h2>
                    <table id="calendario">
                        <thead>
                        <tr>
                            <th>Hora/Dia</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
            </div>

            <div class="modal-footer" style="min-width: 20%;">
                <div class="row" style="border: 1px solid;">
                    <input type="text" style="display: none;" id="id_paciente" name="id_paciente" value="<?php echo htmlspecialchars($id_paciente); ?>">
                    <h3>Medicamento</h3>
                    <h4>Dia seleccionado:</h4>
                    <!-- <label>id</label> -->
                    <input type="text" id="dia" name="dia" required  placeholder="seleccione el dia">
                    <h4>Hora seleccionada:</h4>
                    <!-- <label for="hora">hora</label> -->
                    <input type="text" id="hora" name="hora" required placeholder="seleccione la hora">
                    <h4>Ingrese el medicamento a tomar:</h4>
                    <input type="text" id="nombre_medicamento" required name="nombre_medicamento" placeholder="seleccionar" style="max-width: parent;">
                    <h4>Ingrese la dosis:</h4>
                    <div class="row">
                        <div class="col-6">
                            <input type="number" id="dosis" name="dosis" required placeholder="ingrese" style="max-width: parent;">
                        </div>
                        <div class="col-6">
                            <select name="dosis_num" id="dosis_num">
                                <option value="mg">mg</option>
                                <option value="ml">ml</option>
                                <option value="unidades">unidades</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" style="margin-top: 10px;">Guardar</button>
                </div>

                
                    <!-- <table id="tabla">
                        <thead>
                        <tr>
                            <th>Hora/Dia</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Envia</td>
                                <td id="confirmar-hora">test</td>
                            </tr>
                            <tr><td><button type="submit">Enviar</button></td></tr>
                        </tbody>
                    </table> -->
            </div>
        </form>
    </div>
</div>


<script>
    console.log(medicamentosRegistrados);
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

    // Funciones para abrir y cerrar el modal
    $("#abrirModalCalendario").click(function () {
        $("#modalCalendario").fadeIn();
    });

    $("#cerrarModalCalendario").click(function () {
        $("#modalCalendario").fadeOut();
    });

    $(window).click(function (event) {
        if (event.target.id === "modalCalendario") {
            $("#modalCalendario").fadeOut();
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
        var descripcion = $("#descripcion").val();

        if (!doctor || !fecha || !horaSeleccionada || !paciente || !descripcion) {
            alert("Por favor, complete todos los campos.");
            return;
        }

        console.log("Datos enviados:", {
            doctor: doctor,
            fecha: fecha,
            hora: horaSeleccionada,  
            paciente: paciente,
            descripcion: descripcion
        });

        $.ajax({
            url: "BD/guardarCita.php",
            type: "POST",
            data: {
                doctor: doctor,
                fecha: fecha,
                hora: horaSeleccionada,  
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
})
const dias = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
  const horaInicio = 6; // 6 AM
  const horaFin = 22; // 10 PM
  const table = document.getElementById("calendario");

  // Crear encabezado con los días
  const headerRow = table.querySelector("thead tr");
  dias.forEach(dia => {
    const th = document.createElement("th");
    th.textContent = dia;
    headerRow.appendChild(th);
  });

  // Crear filas por hora
  const tbody = table.querySelector("tbody");
  for (let hora = horaInicio; hora <= horaFin; hora++) {
    const row = document.createElement("tr");
    
    // Celda con la hora
    const horaCelda = document.createElement("td"); 
    const formatoHora = (hora <= 12 ? hora : hora - 12) + (hora < 12 ? " AM" : " PM");
    const formatoHora2 = hora.toString().padStart(2, '0')+":00:00"
    horaCelda.textContent = formatoHora2;
    row.appendChild(horaCelda);

    // Celdas con botones para cada día

    dias.forEach(dia => {
      const td = document.createElement("td");

      const med = medicamentosRegistrados.find(m => m.dia === dia && m.hora === formatoHora2);
      console.log(`${medicamentosRegistrados}`);

      const boton = document.createElement("button");
      boton.textContent = "X";
      boton.type = "button";
      boton.style.paddingTop = "0px"
      boton.style.paddingBottom = "0px"
      boton.style.maxHeight = "20px"

      if (med) {
        boton.textContent = 'X';
        boton.style.backgroundColor = "#d1bae3"; // verde claro

        // Crear etiqueta de dosis fuera del botón
        const etiquetaDosis1 = document.createElement("div");
        etiquetaDosis1.innerHTML = `<strong> ${med.nombre_medicamento}</strong>`;
        etiquetaDosis1.style.fontSize = "11px";
        etiquetaDosis1.style.marginTop = "4px";
        etiquetaDosis1.style.color = "#333";
        etiquetaDosis1.style.border = "1px"

        const etiquetaDosis = document.createElement("div");
        etiquetaDosis.textContent = `Dosis: ${med.dosis}`;
        etiquetaDosis.style.fontSize = "11px";
        etiquetaDosis.style.marginTop = "4px";
        etiquetaDosis.style.color = "#333";
        
        td.appendChild(etiquetaDosis1);
        td.appendChild(etiquetaDosis);
        td.appendChild(boton);
      } else {
        boton.textContent = "X";
        td.appendChild(boton);
      }

      boton.onclick = () => {
        alert(`Día: ${dia}\nHora: ${formatoHora}`);
        const celdaInfo = document.getElementById("hora").value = `${formatoHora2}`;
        const celdaInfoDia = document.getElementById("dia").value = `${dia}`;
      };
      
      row.appendChild(td);
    });

    tbody.appendChild(row);
  }

  document.getElementById("formReservaCalendario").addEventListener("submit", function(e) {
    e.preventDefault();  // evita que recargue o vaya a otra página

    var id_paciente = "<?php echo htmlspecialchars($id_paciente); ?>";
    var nombre_medicamento = $("#nombre_medicamento").val();
    var dosis = $("#dosis").val();
    var hora = $("#hora").val();          
    var dia = $("#dia").val();

    //alert("Por favor, complete todos los campos.");
    alert(`${id_paciente} ${nombre_medicamento} ${dosis} ${hora} ${dia}`);

    if (!id_paciente || !nombre_medicamento || !dosis || !hora || !dia) {
            alert("Por favor, complete todos los campos.");
            return;
        }
    
    console.log("Datos enviados:", {
        is_paciente: id_paciente,
        nombre_medicamento: nombre_medicamento,
        dosis: dosis,  
        hora: hora,
        dia: dia
    });

    $.ajax({
        url: "BD/guardarMedicacion.php",
        type: "POST",
        data: {
            id_paciente: id_paciente,
            nombre_medicamento: nombre_medicamento,
            dosis: dosis,
            hora: hora,
            dia: dia
        },
        success: function(respuesta) {
            $("#modalCalendario").fadeOut();
            alert(respuesta);  // lo que devuelva PHP
        },
        error: function(xhr, status, error) {
            alert("Ocurrió un error: " + error);
        }
    })
});
</script>




</body>
</html>
