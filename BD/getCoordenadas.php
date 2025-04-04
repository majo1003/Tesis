<?php
include 'conexion.php';

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// ID del cliente predeterminado (1)
$cliente_id = 1;

// Consultar las coordenadas más recientes para el cliente con ID 1
$sql = "SELECT latitud, longitud, fecha FROM coordenadas WHERE id_paciente = $cliente_id ORDER BY fecha DESC LIMIT 1";

// Ejecutar la consulta y verificar si tiene éxito
$result = $conn->query($sql);

if (!$result) {
    // Si la consulta falla, mostrar el error de MySQL
    die("Error en la consulta: " . $conn->error);
}

$coordenadas = array();

if ($result->num_rows > 0) {
    // Recuperar las coordenadas
    while ($row = $result->fetch_assoc()) {
        // Recuperar latitud y longitud (ya en formato decimal)
        $latitudDecimal = $row['latitud'];  // Latitud ya está en formato decimal
        $longitudDecimal = $row['longitud'];  // Longitud ya está en formato decimal

        // Agregar las coordenadas al array
        $coordenadas[] = [
            'latitud' => $latitudDecimal,
            'longitud' => $longitudDecimal,
            'fecha' => $row['fecha']
        ];
    }

    // Responder con el formato JSON correcto
    echo json_encode($coordenadas);
} else {
    echo json_encode(["status" => "error", "message" => "No hay coordenadas disponibles"]);
}

$conn->close();
?>
