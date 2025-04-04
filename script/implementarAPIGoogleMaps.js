let map;

async function initMap(lat, lng) {
  // Importar la librería de Google Maps
  const { Map } = await google.maps.importLibrary("maps");

  // Crear el mapa con las coordenadas obtenidas
  map = new Map(document.getElementById("map"), {
    center: { lat, lng },
    zoom: 15,
  });
  
  // Crear un marcador en el mapa
  const marker = new google.maps.Marker({
    position: { lat, lng },
    map: map,
    title: "Ubicación del Paciente"
  });

  // Mostrar la información del marcador cuando se haga clic
  const infoWindow = new google.maps.InfoWindow();
  marker.addListener("click", function () {
    infoWindow.setContent(`<div><p>Latitud: ${lat.toFixed(6)}</p><p>Longitud: ${lng.toFixed(6)}</p></div>`);
    infoWindow.open(map, marker);
  });
}

// Función para obtener las coordenadas desde el servidor PHP
function obtenerCoordenadas() {
  fetch('/BD/getCoordenadas.php')
    .then(response => response.json())
    .then(data => {
      if (data && data.length > 0) {
        // Obtener las coordenadas de la base de datos
        let lat = parseFloat(data[0].latitud);
        let lng = parseFloat(data[0].longitud);

        // Asegurarse de que las coordenadas sean negativas por defecto
        if (lat > 0) lat = -lat;
        if (lng > 0) lng = -lng;

        // Inicializar el mapa con las coordenadas obtenidas
        initMap(lat, lng);
      } else {
        // Si no hay coordenadas en la base de datos, usar coordenadas predeterminadas negativas
        alert("No se encontraron coordenadas. Usando coordenada predeterminada.");
        initMap(-0.1807, -78.4678); // Coordenada predeterminada negativa (Ejemplo: Quito, Ecuador)
      }
    })
    .catch(error => {
      console.error('Error al obtener las coordenadas:', error);
      // Si hay un error, usar coordenadas predeterminadas negativas
      initMap(-0.1807, -78.4678); // Coordenada predeterminada negativa (Ejemplo: Quito, Ecuador)
    });
}

// Llamar a la función para obtener las coordenadas y cargar el mapa
obtenerCoordenadas();
