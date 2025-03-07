$(document).ready(function () {
    $("#formReserva").submit(function (event) {
        event.preventDefault();

        var doctor = $("#doctor").val();
        var fecha = $("#fecha").val();
        var hora = $("#horaSeleccionada").val();
        var tipoEnfermedad = $("#tipo_enfermedad").val();

        if (!hora) {
            alert("Por favor, seleccione una hora.");
            return;
        }

        $.ajax({
            url: "BD/guardarCita.php",
            type: "POST",
            data: {
                doctor: doctor,
                fecha: fecha,
                hora: hora,
                tipo_enfermedad: tipoEnfermedad
            },
            success: function (response) {
                $("#mensaje").html(response);
                $("#formReserva")[0].reset();
            },
            error: function () {
                $("#mensaje").html("Error al reservar la cita.");
            }
        });
    });
});
