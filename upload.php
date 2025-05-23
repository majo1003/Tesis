<?php
session_start();

header('Content-Type: application/json');

// Verificar si el usuario está autenticado como doctor
if (!isset($_SESSION['user']) || $_SESSION['role'] !== 'doctor') {
    echo json_encode(['success' => false, 'error' => 'No autenticado']);
    exit;
}

// Incluir el archivo de conexión
include('BD/conexion.php');  // Asegúrate de que la ruta sea correcta

// Activar los errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verificación de que el archivo PHP se está ejecutando correctamente
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file']) && isset($_POST['id_cita'])) {
    $file = $_FILES['file'];
    $id_cita = intval($_POST['id_cita']);  // Convertir a entero para evitar inyecciones

    // Verificar errores de carga
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode([
            'success' => false,
            'error' => 'Error al cargar el archivo. Código de error: ' . $_FILES['file']['error']
        ]);
        exit;
    }

    // Obtener el contenido binario del archivo
    $fileContent = file_get_contents($file['tmp_name']);

    // Validar el tipo de archivo
    $fileType = mime_content_type($file['tmp_name']); // Obtener el tipo MIME del archivo
    $allowedTypes = ['application/pdf']; // Permitir solo PDFs

    if (!in_array($fileType, $allowedTypes)) {
        echo json_encode(['success' => false, 'error' => 'Tipo de archivo no permitido. Solo se permiten archivos PDF.']);
        exit;
    }

    // Validar el tamaño del archivo (por ejemplo, máximo 5MB)
    if ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(['success' => false, 'error' => 'El archivo es demasiado grande.']);
        exit;
    }

    // Insertar el archivo en la base de datos como un LONGBLOB
    $stmt = $conn->prepare("INSERT INTO archivo (id_cita, ruta, tipo) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $id_cita, $fileContent, $fileType);

    if ($stmt->execute()) {
        // Si el archivo se subió correctamente, actualizar el estado de la cita a 'Atendida'
        $updateStmt = $conn->prepare("UPDATE cita SET atendida = 1 WHERE id_cita = ?");
        $updateStmt->bind_param("i", $id_cita);

        if ($updateStmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Archivo subido y cita marcada como atendida.']);
        } else {
            echo json_encode(['success' => false, 'error' => 'Archivo subido, pero no se pudo actualizar el estado de la cita.']);
        }
        
        $updateStmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => 'Error al insertar el archivo en la base de datos.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No se ha enviado un archivo o un id_cita.']);
}

// Cerrar la conexión
$conn->close();
?>
