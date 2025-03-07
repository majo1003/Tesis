document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.querySelector(".login-form");

  if (loginForm) {
      loginForm.addEventListener("submit", function (event) {
          event.preventDefault(); // Evita el envío del formulario por defecto

          // Obtener valores del formulario
          const email = document.getElementById("correoElectronico").value;
          const password = document.getElementById("contrasena").value;

          // Crear un objeto con los datos
          const formData = new FormData();
          formData.append('correo', email);
          formData.append('contrasena', password);

          // Enviar los datos al servidor con fetch
          console.log("Enviando datos al servidor:", Object.fromEntries(formData));
          fetch('/BD/login.php', {
              method: 'POST',
              body: formData
          })
          .then(response => {
              console.log("Respuesta cruda del servidor:", response); // Verifica la respuesta cruda
              // Asegúrate de que la respuesta sea válida y JSON
              if (!response.ok) {
                  throw new Error('Error en la solicitud: ' + response.statusText);
              }
              return response.json(); // Ahora parseamos directamente como JSON
          })
          .then(data => {
              console.log("Datos procesados del servidor:", data);

              if (data.success) {
                  // Almacenar los datos del usuario en el localStorage si el login es exitoso
                  localStorage.setItem("loggedInUser", JSON.stringify(data.user));

                  // Redirigir según el rol del usuario
                  if (data.role === 'doctor') {
                      window.location.href = "/paginaPrincipalDoctor.html";  // Redirigir al área de doctores
                  } else if (data.role === 'paciente') {
                      window.location.href = "/paginaPrincipalPaciente.html";  // Redirigir al área de pacientes
                  }
              } else {
                  // Si el login falla, mostrar mensaje de error
                  alert(data.message || "Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.");
              }
          })
          .catch(error => {
              console.error("Error en la solicitud:", error);
              alert("Hubo un error en la autenticación. Por favor, inténtalo de nuevo.");
          });
      });
  }
});
