<?php

include 'conexion.php';

// Asegúrate de que la cabecera esté configurada para aceptar solicitudes JSON
header('Content-Type: application/json');

// Leer la entrada JSON
$input = json_decode(file_get_contents('php://input'), true);

// Verificar que los datos están presentes
if (isset($input['lat']) && isset($input['lng'])) {
    $lat = $input['lat'];
    $lng = $input['lng'];

    // Convertir lat y lng a números flotantes
    $lat = floatval($lat);
    $lng = floatval($lng);

    // Verificar si las coordenadas son válidas
    if ($lat != 0 && $lng != 0) {
        // Aquí puedes procesar las coordenadas, por ejemplo, guardarlas en una base de datos
        // Conexión a la base de datos y consulta de inserción
        if ($conn->connect_error) {
            die("Conexión fallida: " . $conn->connect_error);
        }

        $sql = "INSERT INTO coordenadas (latitud, longitud, id_paciente) VALUES ($lat, $lng, 1  )";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "lat" => $lat, "lng" => $lng]);
        } else {
            echo json_encode(["status" => "error", "message" => "Error al insertar datos: " . $conn->error]);
        }

        $conn->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Coordenadas no válidas"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Faltan datos"]);
}
?>
