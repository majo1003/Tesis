<?php
// Incluir la conexión
include('conexion.php');

// Verificar si se han recibido los datos del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Obtener datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    // Verificar que las contraseñas coincidan
    if ($password !== $confirmPassword) {
        echo "Las contraseñas no coinciden.";
        exit;
    }

    // Cifrar la contraseña (con hash)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Consulta SQL para insertar el paciente
    $sql = "INSERT INTO paciente (nombre, apellido, correo, contraseña) 
            VALUES ('$nombre', '$apellido', '$correo', '$hashedPassword')";

    // Ejecutar la consulta
    if ($conn->query($sql) === TRUE) {
        // Redirigir al index.php después de un registro exitoso
        header("Location: index.php");
        exit; // Importante para asegurarse de que el script se detenga después de la redirección
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Cerrar la conexión
    $conn->close();
}
?>
