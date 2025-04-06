<?php
// Conectar a la base de datos
include 'conexion.php';

// Verificar si el parámetro idArchivo está presente en la URL
if (isset($_GET['idArchivo'])) {
    $idArchivo = $_GET['idArchivo'];

    // Preparar la consulta para obtener el tipo de archivo y los datos del archivo
    $sql = "SELECT tipo, ruta FROM archivo WHERE id_archivo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idArchivo);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($tipo, $archivo_data);

    // Si el archivo existe en la base de datos
    if ($stmt->fetch()) {
        // Definir los encabezados para la descarga del archivo
        header("Content-Type: $tipo"); // Tipo de contenido del archivo
        header("Content-Disposition: attachment; filename=archivo_$idArchivo.pdf"); // Nombre del archivo de descarga
        header("Content-Length: " . strlen($archivo_data)); // Longitud del archivo

        // Enviar el archivo BLOB al navegador para la descarga
        echo $archivo_data;
    } else {
        // Si no se encuentra el archivo en la base de datos
        echo "Archivo no encontrado.";
    }

    // Cerrar la conexión y liberar recursos
    $stmt->close();
    $conn->close();
} else {
    // Si no se ha pasado el parámetro idArchivo
    echo "No se ha especificado el archivo.";
}
?>
