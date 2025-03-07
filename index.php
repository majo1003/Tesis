<?php
session_start();

// Si el usuario ya está logueado, redirigirlo a la página principal según su rol
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'paciente') {
        header("Location: paginaPrincipalPaciente.php");
        exit;
    } elseif ($_SESSION['role'] == 'doctor') {
        header("Location: paginaPrincipalAdmin.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Gestor Pacientes</title>
</head>

<body>

    <h1 class="dr-h1-login">Bienvenido al Gestor de pacientes</h1>

    <main>
        <section id="login">
            <div class="login-container">
                <form action="BD/login.php" method="POST" class="login-form">
                    <div class="form-group">
                        <label for="correoElectronico">Correo Electrónico:</label>
                        <input type="email" name="correo" id="correoElectronico" placeholder="example@example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="contrasena">Contraseña:</label>
                        <input type="password" name="contrasena" id="contrasena" placeholder="contraseña" required>
                    </div>
                    <div class="form-groupTerm">
                        <input type="checkbox" name="terminosCondiciones" id="terminos" required>
                        <label for="terminos">Acepto los <a href="#">términos y condiciones</a></label>
                    </div>
                    <button type="submit" class="btn-primary">Iniciar Sesión</button>
                </form>
                <div class="login-links dr-gap10">
                    <button type="button" class="btn-secondary">¿Olvidaste tu contraseña?</button>
                    <button type="button" class="btn-secondary" onclick="window.location.href='registrarAhora.php'">Regístrate ahora</button>
                </div>
            </div>
        </section>
    </main>

</body>

</html>
