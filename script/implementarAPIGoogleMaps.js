// Nota: Este ejemplo requiere que consientas compartir tu ubicación cuando 
// sea solicitado por tu navegador. Si ves el error "El servicio de Geolocalización falló", 
// significa que probablemente no diste permiso para que el navegador te localice.

let map, infoWindow;

function initMap() {
  map = new google.maps.Map(document.getElementById("map"), {
    center: { lat: -0.1807, lng: -78.4678 },
    zoom: 13,
  });
  infoWindow = new google.maps.InfoWindow();

  const locationButton = document.createElement("button");
  locationButton.textContent = "Ubicación en tiempo real";
  locationButton.classList.add("custom-map-control-button");
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);

  locationButton.addEventListener("click", () => {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
        (position) => {
          const pos = {
            lat: position.coords.latitude,
            lng: position.coords.longitude,
          };

          // Datos adicionales
          const accuracy = position.coords.accuracy; // Precisión en metros
          const altitude = position.coords.altitude; // Altitud, puede ser null
          const speed = position.coords.speed; // Velocidad en m/s, puede ser null
          const timestamp = new Date(position.timestamp).toLocaleString(); // Fecha y hora

          const contentString = `
            <div>
              <p><strong>Ubicación encontrada:</strong></p>
              <p>Latitud: ${pos.lat.toFixed(6)}</p>
              <p>Longitud: ${pos.lng.toFixed(6)}</p>
              <p>Precisión: ${accuracy.toFixed(2)} metros</p>
              ${altitude !== null ? `<p>Altitud: ${altitude.toFixed(2)} metros</p>` : ""}
              ${speed !== null ? `<p>Velocidad: ${speed.toFixed(2)} m/s</p>` : ""}
              <p>Hora de la localización: ${timestamp}</p>
            </div>
          `;

          infoWindow.setPosition(pos);
          infoWindow.setContent(contentString);
          infoWindow.open(map);
          map.setCenter(pos);
        },
        () => {
          handleLocationError(true, infoWindow, map.getCenter());
        },
      );
    } else {
      handleLocationError(false, infoWindow, map.getCenter());
    }
  });
}

function handleLocationError(browserHasGeolocation, infoWindow, pos) {
  infoWindow.setPosition(pos);
  infoWindow.setContent(
    browserHasGeolocation
      ? "Error: El servicio de Geolocalización falló."
      : "Error: Tu navegador no soporta geolocalización.",
  );
  infoWindow.open(map);
}

window.initMap = initMap;
