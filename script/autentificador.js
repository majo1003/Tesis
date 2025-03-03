const users = [
  { email: "admin@example.com", password: "admin123", role: "admin" },
  { email: "user@example.com", password: "user123", role: "user" }
];

document.addEventListener("DOMContentLoaded", function () {
  const loginForm = document.querySelector(".login-form");
  if (loginForm) {
    loginForm.addEventListener("submit", function (event) {
      console.log("después de ingresar");
      event.preventDefault(); // Evita el envío del formulario por defecto

      // Obtener valores del formulario
      const email = document.getElementById("correoElectronico").value;
      const password = document.getElementById("contrasena").value;

      // Buscar al usuario en la lista
      const user = users.find(
        (u) => u.email === email && u.password === password
      );

      if (user) {
        // Almacenar el rol del usuario en el almacenamiento local
        localStorage.setItem("loggedInUser", JSON.stringify(user));

        // Redirigir según el rol del usuario
        if (user.role === "admin") {
          window.location.href = "paginaPrincipalAdmin.html";
        } else if (user.role === "user") {
          window.location.href = "paginaPrincipalPaciente.html";
        }
      } else {
        // Mostrar mensaje de error si no coincide
        alert("Correo electrónico o contraseña incorrectos. Por favor, inténtalo de nuevo.");
      }
    });
  }
});
