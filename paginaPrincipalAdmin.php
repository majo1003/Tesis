<?php
session_start(); // Iniciar la sesión
include 'BD/conexion.php';

// Verificar si el usuario está autenticado como doctor
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor') {
    // Si no está autenticado, redirigir al login
    header("Location: index.php");
    exit;
}

// Verificar si existe el ID del doctor en la sesión
if (!isset($_SESSION['id_doctor'])) {
    die("Error: ID del doctor no encontrado en la sesión.");
}

$id_doctor = $_SESSION['id_doctor'];

// Obtener el nombre del doctor
$queryDoctor = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_completo FROM doctor WHERE id_doctor = ?";
$stmtDoctor = $conn->prepare($queryDoctor);
$stmtDoctor->bind_param("i", $id_doctor);
$stmtDoctor->execute();
$resultDoctor = $stmtDoctor->get_result();
$nombreDoctor = "Doctor"; // Valor por defecto

if ($row = $resultDoctor->fetch_assoc()) {
    $nombreDoctor = $row['nombre_completo'];
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script type="module" src="script/implementarAPIGoogleMaps.js"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAbEJN9YwUlDW1TrU2VokmDJBW5-dWQK5E&callback=initMap&v=weekly"
        defer></script>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles-crud.css">
    <link rel="stylesheet" href="css/styles-profile.css">
    <title>Home</title>
</head>

<body>

    <header class="header-pantalla">
        <div class="titulo">
        <h1>Bienvenido <?php echo htmlspecialchars($nombreDoctor); ?></h1>
        </div>

        <div class="cerrar-sesion">
            <a href="profileDoctor.php" class="dr-buttonHeader">Editar Perfil</a>
        </div>

        <div class="cerrar-sesion">
            <a href="BD/cerrarSesion.php" class="dr-buttonHeader">Cerrar Sesión</a>
        </div>

        <div id="alerta">¡Estás fuera del rango permitido!</div>

    </header>



    <main class="pantalla-principal">
        <header id="menu-lateral">
            <div class="manu">
                <nav>
                    <ul>
                        <li><button class="nav-link" data-target="calendar" href="#"> <img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAALNJREFUSEvdlUEKgzAQRZ/nEATpot7Gy3TTG7QX6kHcuBMF79FSMBQi099Mq1izCzPz//yfSZKx8MoWxkcR3KcGrDwVX5+gBi5A5bSuBU7ALdTH0kcgd4KHsg44WATB0y85XtbHClYjUNNlKZxNlaVgPwSx5NT97KKlAqh8SZA6rts7ZGWBikuLFICKS4L/P4OfKxiAIhU1yu+B0nqunx/OFTg6SRrg/O7DceLaZd5X8+NGHks5NBnCpFrAAAAAAElFTkSuQmCC" />
                                Calendario</button>
                        </li>
                        <li><button href="#" class="nav-link" data-target="clientes"><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAARlJREFUSEu11c8qxUEUwPHPtfAGFkpKKcXCU3gA5R1sJJEssMFNSSkLGwtr5Q28hA1lx4IsPIHyb+p3pXF/9878zG/WZ77fOXPOzOloeXVa5osFSzjATEPxPXZw1dsfC24x2xDe23aHuTrB5z/hf7hxBoME+zirCCvYHnCYH26qYB0nETAIQr36rWzBGF4j0jieSwkC7KVNwRaOIsEu9kplEDhdnGIUq9gsWeTc7s0uchA84qEyTWGyVAaXuMA1PiroCBawjMU+oqQM3r5bMzyo8yH3s4HjKCZJED6tUNiUFR7h2q/AJMEEnlLomMdNriBnVoSavOcKEg9f5i/KldXWoPWBE0bmIaZzj1zFDx2ZDbn123I6pZH8C+MWNBk3lDwfAAAAAElFTkSuQmCC" />
                                Clientes</button>
                        </li>
                        <li><button href="#" class="nav-link" data-target="tasks"><img
                                    src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAARVJREFUSEvl1TtKBEEQxvHfgpGhkeAbBU08gqIn8ABew8TUSI/hJYzXF8aamPhEAyNjE1EKZmTopXfW3l0TOxu66vt/1VNFdYz5dMasrw1wis0WE11s52JSwA4OsVZY2S32cFLnp4BXzBSK12kPWM4BvqqLGlx/tzHT+B/jaQV/Dmhznt6nBnu6qCfgl4SRAqbwnhgYGSA67QzHOGhAigFNtyF+gUXcYR0fFaQIEA53GxN9iYVKfANvw1QwiWus4BETmMM9thCD2TxFFUzjqnqSEHtCOE/F464IEImzOK+s5sSHAkRy/NRPvPSZjeIKBp23fwB4xvyg75GJiy5byu2D2GhHWC2E3GC/30Yr1M2ntS39oYHfAk1CGRiOJxUAAAAASUVORK5CYII=" />
                                Tasks</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </header>

        <section id="calendar">
            <!-- Modal para mostrar la descripción de la cita -->
<div id="citaModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Detalles de la Cita</h2>
        <p id="descripcionCita"></p>
        <p><strong>Fecha y Hora: </strong><span id="fechaHoraCita"></span></p>
    </div>
</div>

            <div>
                <h2>Calendario</h2>
                <div id="calendario"></div>
            </div>
        </section>

<section id="clientes" class="dr-gap20">
    <div class="lista-paciente">
        <div class="titulo">
            <h1 class="dr-h1">Lista de pacientes</h1>
            <span id="totalPaciente" class="dr-span"></span>
        </div>
        <div id="modales-container"></div>
    </div>

    <div class="tabla-pacientes">
        <table class="tabla">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>ID</th>
                    <th>Problema</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla-pacientes-body">
                <!-- Aquí se llenarán los pacientes dinámicamente -->
            </tbody>
        </table>
    </div>
</section>



        <section id="tasks" class="dr-gap10">

<!-- Detalles del paciente -->
<div class="detalles-pacientes dr-gap20">
    <div class="titulos dr-gap10">
        <h1 id="nombre-enfermedad" class="dr-h1">Dolor de huesos</h1>
        <span id="paciente-name" class="dr-spanGris">Juan Valladares</span>
        <div class="estado dr-gap10">
            <span id="paciente-status" class="dr-spanGris">
                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAYVJREFUSEu9ljFLw0AUx/9X4uQguHTTIoqgg5/BwVURHaVcaumig7M4ZBBEFxcdhEAuBDfRwc/gB3BQEApWt4IU55DckyumSEzSu9iaLbl3/9/7v3d5CcOYLzZmfeQCbNveJqJjAIuGSbwwxo48z7tR+3IBnPMnAEuG4kn4sxBieRiAcsR7AO6+1zYBTGfFCSH6yRc5yAJ0LctacV23qzY3m81qFEWPAKppSFnApRBi/6cY5/wCwN6oALdCiK1SAM55vySJteQ+lVkYx/FCEATv6rnjOFan02kDmB3qQBOgdCSAewAfANYAzGg12QCgdXJ/NVkDoEp4FcfxWRAEr4rSaDTmpJSHAHb/WqIeEa37vv+QlX69Xl+tVCqqbJPJupEDItrxff+6qDac8wMA56UAtVptwnGcqAjQarWmwjD8LAXQ6moqSKdEox126VOkxrWU8oQxNm/oIHtcpwGGornhg2n6H4C3vNe+yE3SzLyYgQPbtjeI6NT0E6kNGFXN0zpj/6v4Apsu0RkEP99NAAAAAElFTkSuQmCC" />
            </span>
            <span id="paciente-time" class="dr-spanGris">
            </span>
        </div>
    </div>

    <div class="container-descripcion">
        <div class="imagen-description dr-gap20">
            <div class="imagen">
            <img id="paciente-img" src="" alt="Foto del paciente" height="150px" width="150px">
            </div>
            <div class="descripcion">
                <h2>Descripción</h2>
                <p id="paciente-description" class=""></p>
            </div>
        </div>

        <!-- Problemas y Archivos subidos -->
        <div class="problemas">
            <h2 class="dr-h1">Soluciones Clínicas</h2>
            <ul id="paciente-problems">
                    <!-- Las archivos se cargarán dinámicamente con AJAX -->
            </ul>
        </div>
        
    </div>

    <!-- Mapa del paciente -->
    <div class="ubicacion-paciente">
        <h1>Localización del paciente</h1>
        <div id="map"></div>
    </div>
</div>

<!-- Ingreso de datos -->
<div class="ingreso-datos">
    <div class="detalles-paciente">
        <h2 class="dr-h1">Detalles del paciente</h2>
        <p>Nombre : <span id="paciente-name2"></span></p>
        <p>Edad: <span id="paciente-age"></span></p>
        <p>Ubicación: <span id="paciente-location"></span></p>
    </div>

    <div class="file-stack">
    <h2 class="dr-h1">Datos</h2>
    <p>Última modificación: 2024 Julio 14</p>
    <p>Documento: </p>
    
    <!-- Formulario para enviar los datos -->
    <form id="uploadForm" enctype="multipart/form-data">
        <!-- Campos ocultos para enviar id_doctor e id_cita -->
        <input type="hidden" id="idDoctor" value="<?php echo $_SESSION['id_doctor']; ?>">        
        <div class="upload-area" id="uploadArea">
            <p>Arrastra y suelta tu archivo aquí o</p>
            <label for="fileInput" class="upload-label">
                Seleccionar archivo
                <input type="file" id="fileInput" name="file" class="file-input" />
            </label>
        </div>

        <button type="submit" id="submitButton" class="dr-button">Enviar</button>
        <p id="fileName" class="file-name"></p>
    </form>
</div>

</div>
</section>
    </main>

    <footer>
        <p>&copy; 2024 Maria Jose Encalada. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            console.log("jQuery cargado correctamente.");
        });
    </script>

    <script>
            function mostrarUbicacionPaciente(idPaciente) {
        fetch(`BD/getPacienteUbicacion.php?id=${idPaciente}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error("Error en la carga de datos:", data.error);
                    alert('No se pudo cargar la ubicación del paciente.');
                    return;
                }

                // Muestra la ubicación del paciente
                const pacienteLocation = data.ubicacion;

                if (pacienteLocation) {
                    // Mostrar los datos de ubicación en el mapa
                    mostrarMapa(pacienteLocation);
                } else {
                    alert('Ubicación no disponible para este paciente');
                }
            })
            .catch(error => {
                console.error('Error al obtener los datos del paciente:', error);
                alert('Hubo un problema al obtener los datos.');
            });
    }

    // Llamada inicial para obtener los archivos cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        const idCita = document.getElementById('idCita').value;
        if (idCita) {
            obtenerArchivos(idCita);
        }
    });
    </script>


    <script>
            // Espera a que todo el DOM esté completamente cargado
            document.addEventListener('DOMContentLoaded', function() {
            const element = document.getElementById("paciente-name");

            if (element) {
                // Si el elemento existe, se actualiza su texto
                element.textContent = "Nuevo valor";
            } else {
                // Si el elemento no existe, muestra un mensaje de error
                console.log("El elemento no existe");
            }
        });
    </script>



    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            events: {
                url: 'BD/eventos.php',
                failure: function() {
                    console.error("❌ No se pudo cargar eventos desde eventos.php");
                }
            },
            loading: function(isLoading) {
                if (isLoading) {
                    console.log("⏳ Cargando eventos...");
                } else {
                    console.log("✅ Eventos cargados");
                }
            },
            eventDidMount: function(info) {
                console.log("📅 Evento montado:", info.event);
            },
            eventClick: function(info) {
                alert(`
                    Descripción: ${info.event.title}
                    Fecha y Hora: ${info.event.start.toLocaleString()}
                    Tipo de Enfermedad: ${info.event.extendedProps.tipo_enfermedad}
                    Atendida: ${info.event.extendedProps.atendida == 1 ? 'Sí' : 'No'}
                `);
            }
        });

        calendar.render();
    });

    </script>

    <script src="script/secciones.js"></script>
    <script src="script/ingresarDocumento.js"></script>
    <script src="script/obtencionDatosPaciente.js"></script>

    <script>
// Coordenadas de referencia fijas (en decimal)
const latReferencia = -0.207085;
const lonReferencia = -78.489723;

// Distancia máxima permitida (en metros)
const distanciaMaxima = 20;

// Función para calcular la distancia entre dos puntos geográficos (fórmula Haversine)
function calcularDistanciaEnMetros(lat1, lon1, lat2, lon2) {
    const R = 6371000; // Radio de la Tierra en metros
    const rad = Math.PI / 180;

    const dLat = (lat2 - lat1) * rad;
    const dLon = (lon2 - lon1) * rad;

    const a = Math.sin(dLat / 2) ** 2 +
              Math.cos(lat1 * rad) * Math.cos(lat2 * rad) *
              Math.sin(dLon / 2) ** 2;

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    return R * c;
}

// Función para verificar la ubicación del paciente
function verificarUbicacion(lat, lon) {
    const latNumerica = parseFloat(lat);
    const lonNumerica = parseFloat(lon);

    if (isNaN(latNumerica) || isNaN(lonNumerica)) {
        console.error("Coordenadas no válidas:", lat, lon);
        return;
    }

    const distancia = calcularDistanciaEnMetros(latReferencia, lonReferencia, latNumerica, lonNumerica);
    console.log(`Distancia calculada: ${distancia.toFixed(2)} metros`);

    if (distancia > distanciaMaxima) {
        mostrarAlerta();
    } else {
        ocultarAlerta();
    }
}

// Mostrar alerta
function mostrarAlerta() {
    const alertaElement = document.getElementById('alerta');
    if (alertaElement) {
        alertaElement.style.display = 'block';
    }
}

// Ocultar alerta
function ocultarAlerta() {
    const alertaElement = document.getElementById('alerta');
    if (alertaElement) {
        alertaElement.style.display = 'none';
    }
}

// Obtener coordenadas del paciente desde el backend
function obtenerCoordenadasPaciente(idPaciente) {
    fetch(`BD/getCoordenadas.php?id_paciente=${idPaciente}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                const latPaciente = parseFloat(data[0].latitud);
                const lonPaciente = parseFloat(data[0].longitud);
                verificarUbicacion(latPaciente, lonPaciente);
            } else {
            }
        })
        .catch(error => {
            console.error('Error al obtener las coordenadas:', error);
            alert('Hubo un problema al obtener las coordenadas.');
        });
}

// Iniciar la verificación al cargar la página
document.addEventListener('DOMContentLoaded', function () {
    const idPaciente = 1; // Ajustar según sea necesario
    obtenerCoordenadasPaciente(idPaciente);
});
</script>



</body>

</html>

