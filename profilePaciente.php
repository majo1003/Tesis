<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles-profile.css ">
    <title>Profile</title>
</head>
<body>
    
    <header class="header-pantalla">
        <div class="titulo">
            <h1>Bienvenido Administrador</h1>
        </div>

        <div class="cerrar-sesion">
            <a href="BD/cerrarSesion.php" class="dr-buttonHeader">Cerrar Sesión</a>
        </div>

        <div class="cerrar-sesion">
            <button onclick="location.href='paginaPrincipalPaciente.php'">Regresar</button>
        </div>
    </header>

    <section id="Profile">
        <h2>Profile</h2>
        <img src="https://via.placeholder.com/150" alt="Profile Picture" class="profile-pic" id="profilePic">
        <p><strong>Name:</strong> <span id="profileName">John Doe</span></p>
        <p><strong>Age:</strong> <span id="profileAge">30</span></p>
        <p><strong>Location:</strong> <span id="profileLocation">New York, USA</span></p>
        <button onclick="showEditProfile()">Edit Profile</button>
    </section>

    <main>
        <!-- Edit Profile Section -->
        <section id="editProfile">
            <h2>Edit Profile</h2>
            <form id="editProfileForm" method="POST" action="register_patient.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="editPhoto">Photo</label>
                    <input type="file" name="foto" id="editPhoto">
                </div>
                <div class="form-group">
                    <label for="editName">Name</label>
                    <input type="text" name="nombre" id="editName" placeholder="Enter your name">
                </div>
                <div class="form-group">
                    <label for="editAge">Age</label>
                    <input type="number" name="edad" id="editAge" placeholder="Enter your age">
                </div>
                <div class="form-group">
                    <label for="editLocation">Location</label>
                    <input type="text" name="ubicacion" id="editLocation" placeholder="Enter your location">
                </div>
                <button type="submit">Save Changes</button>
            </form>
        </section>
    </main>

    <script>
        function showEditProfile() {
            // Ocultar el perfil y mostrar el editor de perfil
            document.getElementById('Profile').style.display = 'none';
            document.getElementById('editProfile').style.display = 'block';
        }
    </script>
</body>
</html>
