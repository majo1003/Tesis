<?php
include 'BD/conexion.php';
session_start();

// Verificar si el usuario está autenticado y es un paciente
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor') {
    header("Location: index.php");
    exit;
}

// Verificar si existe el ID del paciente en la sesiAón
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/styles-profile.css ">
    <title>Profile</title>
</head>
<body>
    
    <header class="header-pantalla">
        <div class="titulo">
        <h1>Bienvenido <?php echo htmlspecialchars($nombreDoctor); ?></h1>
        </div>

        <div class="cerrar-sesion">
            <a href="paginaPrincipalAdmin.php" class="dr-buttonHeader">Regresar</a>
        </div>

        <div class="cerrar-sesion">
            <a href="BD/cerrarSesion.php" class="dr-buttonHeader">Cerrar Sesión</a>
        </div>
        
    </header>

    <main>
    <section id="Profile">
        <div class="infoPaciente">
        <h2>Profile</h2>
            <img src="https://placekitten.com/150/150" alt="Profile Picture" class="profile-pic" id="profilePic">
            <p><strong>Nombre:</strong> <span id="paciente-name">...</span></p>
            <p><strong>Apellido:</strong> <span id="paciente-apellido">...</span></p>
            <p><strong>Edad:</strong> <span id="paciente-age">...</span></p>
            <p><strong>Ubicación:</strong> <span id="paciente-location">...</span></p>
            <button onclick="showEditProfile()">Edit Profile</button>
        </div>
    </section>
        <!-- Edit Profile Section -->
    <section id="editProfile" style="display: none;"> <!-- Ocultarlo inicialmente -->
        <h2>Edit Profile</h2>
        <form id="editProfileForm" method="POST" action="BD/actualizarDoctor.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="editPhoto">Photo</label>
                <input type="file" name="foto" id="editPhoto" accept="image/jpeg, image/png">
            </div>
            <div class="form-group">
                <label for="editName">Name</label>
                <input type="text" name="nombre" id="editName" placeholder="Ingresa tu nombre">
            </div>
            <div class="form-group">
                <label for="editApellido">Apellido</label>
                <input type="text" name="apellido" id="editApellido" placeholder="Ingresa tu apellido">
            </div>
            <div class="form-group">
                <label for="editAge">Age</label>
                <input type="number" name="edad" id="editAge" placeholder="Ingresa tu edad">
            </div>
            <div class="form-group">
                <label for="editLocation">Location</label>
                <input type="text" name="ubicacion" id="editLocation" placeholder="Ingresa tu ubicación">
            </div>
            <div>
                <button type="submit">Save Changes</button>
                <button type="button" onclick="cancelEdit()">Cancel</button>
            </div>
        </form>
    </section>

    </main>
    <footer>
        <p>&copy; 2024 Maria Jose Encalada. Todos los derechos reservados.</p>
    </footer>

<script>
    function showEditProfile() {
        document.getElementById('Profile').style.display = 'none';
        document.getElementById('editProfile').style.display = 'block';
    }

    function cancelEdit() {
    document.getElementById('editProfile').style.display = 'none';
    document.getElementById('Profile').style.removeProperty('display');
    document.getElementById("editProfileForm").reset();
}

</script>


<script>
    const idPaciente = <?php echo json_encode($_SESSION['id_doctor']); ?>;

    document.addEventListener("DOMContentLoaded", function () {
        if (idPaciente) {
            obtenerPaciente(idPaciente);
        } else {
            console.error("ID del doctor no está definido.");
        }
    });

    function obtenerPaciente(idPaciente) {
    fetch(`BD/getDoctorInfo.php?id=${idPaciente}`)
        .then(response => response.text())
        .then(data => {
            console.log('Respuesta recibida:', data);
            try {
                const jsonData = JSON.parse(data);

                if (jsonData.error) {
                    console.error("Error:", jsonData.error);
                    alert(jsonData.error);
                    return;
                }

                if (jsonData.foto) {
                    const fotoElement = document.getElementById('profilePic');
                    fotoElement.src = `data:image/jpeg;base64,${jsonData.foto}`;
                }

                // Mostrar en vista de perfil
                document.getElementById("paciente-name").textContent = jsonData.nombre || "Desconocido";
                document.getElementById("paciente-apellido").textContent = jsonData.apellido || "Desconocido";
                document.getElementById("paciente-age").textContent = jsonData.edad || "No disponible";
                document.getElementById("paciente-location").textContent = jsonData.ubicacion || "No especificada";

                // Prellenar formulario
                document.getElementById("editName").value = jsonData.nombre || "";
                document.getElementById("editApellido").value = jsonData.apellido || "";
                document.getElementById("editAge").value = jsonData.edad || "";
                document.getElementById("editLocation").value = jsonData.ubicacion || "";

            } catch (e) {
                console.error("Error al parsear JSON:", e);
            }
        })
        .catch(error => {
            console.error("Error en la solicitud:", error);
            alert('Hubo un problema al obtener los datos del paciente.');
        });
}

</script>



</body>
</html>
