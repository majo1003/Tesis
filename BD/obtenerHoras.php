<?php
include 'conexion.php';

if (!isset($_POST['doctor']) || !isset($_POST['fecha'])) {
    echo "❌ Faltan parámetros doctor o fecha";
    exit;
}

$doctor = $_POST['doctor'];
$fecha = $_POST['fecha'];

// Consulta para obtener las franjas horarias y verificar su disponibilidad
$sql = "SELECT H.id_hora, H.hora_inicio, H.hora_fin, 
               IFNULL(HD.estado, 1) AS estado
        FROM Horas H
        LEFT JOIN HorarioDisponibilidad HD 
        ON H.id_hora = HD.id_hora 
        AND HD.id_doctor = ? 
        AND HD.fecha = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("is", $doctor, $fecha);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        $estado = $row['estado']; // 1 = Disponible, 0 = No disponible
        $clase = ($estado == 0) ? "no-disponible" : "hora-fila";
        $disabled = ($estado == 0) ? "disabled" : "";

        // Mostrar id_hora en el atributo data-id
        echo "<li class='$clase hora-btn' onclick='seleccionarHora(this)' 
                 data-id='" . htmlspecialchars($row['id_hora']) . "' $disabled>";
        echo htmlspecialchars($row['hora_inicio']) . " - " . htmlspecialchars($row['hora_fin']);
        echo "</li>";
    }
    echo "</ul>";
} else {
    echo "⚠️ No hay horarios disponibles.";
}

$stmt->close();
$conn->close();
?>



<style>
.hora-tabla {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.hora-tabla th, .hora-tabla td {
    border: 2px solid black;
    padding: 10px;
    text-align: center;
    cursor: pointer;
}

.hora-tabla tr:hover {
    background-color: #f0f0f0;
}

/* Fila seleccionada */
.hora-fila.seleccionada {
    background-color: #4CAF50;
    color: white;
}

/* Fila no disponible */
.no-disponible {
    background-color: red;
    color: white;
    cursor: not-allowed;
}

/* Evitar selección de horas no disponibles */
.no-disponible:hover {
    background-color: darkred;
}

</style>

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
