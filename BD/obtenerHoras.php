<?php
include 'conexion.php';

if (!isset($_POST['doctor']) || !isset($_POST['fecha'])) {
    echo "❌ Faltan parámetros doctor o fecha";
    exit;
}

$doctor = (int) $_POST['doctor']; // Convertimos a entero para mayor seguridad
$fecha = $_POST['fecha'];  // La fecha ya está en formato correcto

// Consulta SQL corregida: COALESCE(HD.estado, 0) evita valores NULL en 'estado'
$sql = "SELECT H.id_hora, H.hora_inicio, H.hora_fin, 
               COALESCE(HD.estado, 0) AS estado  -- Reemplaza NULL por 0
        FROM horas H
        LEFT JOIN horariodisponibilidad HD 
        ON H.id_hora = HD.id_hora 
        AND HD.id_doctor = $doctor 
        AND HD.fecha = '$fecha'  
        ORDER BY H.hora_inicio";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table class='hora-tabla'>";
    echo "<tr><th>Horas Disponibles</th></tr>"; // Encabezado de la tabla
    
    while ($row = $result->fetch_assoc()) {
        // Convertimos 'estado' explícitamente a entero
        $estado = (int) $row['estado'];

        // Definir la clase CSS en base al estado
        $clase = ($estado === 0) ? "no-disponible" : "disponible";

        // Solo los horarios disponibles tendrán el evento 'onclick'
        $onclick = ($estado === 1) ? "onclick='seleccionarHora(this)'" : "";

        // Mostrar la fila con las clases y atributos adecuados
        echo "<tr class='$clase hora-btn' data-id='" . htmlspecialchars($row['id_hora']) . "' $onclick>";
        echo "<td>" . htmlspecialchars($row['hora_inicio']) . " - " . htmlspecialchars($row['hora_fin']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "⚠️ No hay horarios disponibles.";
}

$conn->close();
?>

<script>

function seleccionarHora(fila) {
    // Verifica si la fila está marcada como no disponible
    if (fila.classList.contains('no-disponible')) {
        return; // Evita que se seleccione una hora no disponible
    }

    // Remueve la selección previa
    document.querySelectorAll('.hora-fila').forEach(f => f.classList.remove('seleccionada'));

    // Marca la fila seleccionada
    fila.classList.add('seleccionada');

    // Guarda el id_hora
    console.log("Hora seleccionada:", fila.dataset.id);  // Aquí estamos obteniendo el id_hora
}


</script>