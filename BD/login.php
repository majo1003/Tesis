<?php
session_start();
include 'conexion.php'; // Conectar a la base de datos

if (!isset($_POST['correo']) || !isset($_POST['contrasena'])) {
    echo json_encode(['success' => false, 'message' => 'Correo y contraseña son requeridos.']);
    exit;
}

$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];

if (empty($correo) || empty($contrasena)) {
    echo json_encode(['success' => false, 'message' => 'Correo y contraseña son requeridos.']);
    exit;
}

// Intentar verificar si es paciente
$sql_paciente = "SELECT * FROM paciente WHERE correo = ? LIMIT 1";
$stmt_paciente = $conn->prepare($sql_paciente);
$stmt_paciente->bind_param('s', $correo);
$stmt_paciente->execute();
$result_paciente = $stmt_paciente->get_result();
$paciente = $result_paciente->fetch_assoc();

if ($paciente) {
    // Verificar si la contraseña está hasheada
    if (strlen($paciente['contraseña']) == 60) { // Si la longitud es 60, se supone que está hasheada
        // Verificar si la contraseña ingresada coincide con el hash de la base de datos
        if (password_verify($contrasena, $paciente['contraseña'])) {
            $_SESSION['user'] = $paciente;
            $_SESSION['id_paciente'] = $paciente['id_paciente']; // Guarda el ID del paciente en sesión
            $_SESSION['role'] = 'paciente';
            header("Location: ../paginaPrincipalPaciente.php");
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
            exit;
        }
    } else {
        // Si la contraseña no está hasheada, comparar directamente con la base de datos (sin hashing)
        if ($contrasena === $paciente['contraseña']) {
            // Hashear la contraseña y actualizar la base de datos
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql_update = "UPDATE paciente SET contraseña = ? WHERE correo = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('ss', $hashedPassword, $correo);
            $stmt_update->execute();

            $_SESSION['user'] = $paciente;
            $_SESSION['id_paciente'] = $paciente['id_paciente']; // Guarda el ID del paciente en sesión
            $_SESSION['role'] = 'paciente';
            header("Location: ../paginaPrincipalPaciente.php");
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
            exit;
        }
    }
}

// Intentar verificar si es doctor
$sql_doctor = "SELECT * FROM doctor WHERE correo = ? LIMIT 1";
$stmt_doctor = $conn->prepare($sql_doctor);
$stmt_doctor->bind_param('s', $correo);
$stmt_doctor->execute();
$result_doctor = $stmt_doctor->get_result();
$doctor = $result_doctor->fetch_assoc();

if ($doctor) {
    // Verificar si la contraseña está hasheada
    if (strlen($doctor['contraseña']) == 60) { // Si la longitud es 60, se supone que está hasheada
        // Verificar si la contraseña ingresada coincide con el hash de la base de datos
        if (password_verify($contrasena, $doctor['contraseña'])) {
            $_SESSION['user'] = $doctor;
            $_SESSION['role'] = 'doctor';
            $_SESSION['id_doctor'] = $doctor['id_doctor']; // Guarda el ID del doctor en sesión
            header("Location: ../paginaPrincipalAdmin.php");
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
            exit;
        }
    } else {
        // Si la contraseña no está hasheada, comparar directamente con la base de datos (sin hashing)
        if ($contrasena === $doctor['contraseña']) {
            // Hashear la contraseña y actualizar la base de datos
            $hashedPassword = password_hash($contrasena, PASSWORD_DEFAULT);
            $sql_update = "UPDATE doctor SET contraseña = ? WHERE correo = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('ss', $hashedPassword, $correo);
            $stmt_update->execute();

            $_SESSION['user'] = $doctor;
            $_SESSION['role'] = 'doctor';
            $_SESSION['id_doctor'] = $doctor['id_doctor']; // Guarda el ID del doctor en sesión
            header("Location: ../paginaPrincipalAdmin.php");
            exit;
        } else {
            echo json_encode(['success' => false, 'message' => 'Contraseña incorrecta']);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => 'Correo o contraseña incorrectos']);
exit;
?>
