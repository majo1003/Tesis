document.addEventListener("DOMContentLoaded", function () {
    // Manejo de cierre de sesión
    const logoutButton = document.querySelector(".dr-buttonHeader");
    if (logoutButton) {
        logoutButton.addEventListener("click", function () {
            console.log("Cierre de sesión activado"); // Para depuración
            localStorage.removeItem("loggedInUser");
            window.location.href = "index.html";
        });
    }

    // Protección de páginas
    const currentPage = window.location.pathname;

    // Verificar autenticación según la página actual
    if (currentPage.includes("paginaPrincipalAdmin.html")) {
        checkAuthentication("admin"); // Solo permite acceso a administradores
    } else if (currentPage.includes("paginaPrincipalPaciente.html")) {
        checkAuthentication("user"); // Solo permite acceso a usuarios
    }
});

// Verificación de autenticación en otras páginas
function checkAuthentication(role) {
    const loggedInUser = JSON.parse(localStorage.getItem("loggedInUser"));

    if (!loggedInUser || loggedInUser.role !== role) {
        // Redirigir al index si no está autenticado o si el rol no coincide
        window.location.href = "index.html";
    } else {
        // Si está autenticado, mostrar el contenido de la página
        document.body.classList.remove("hidden");
    }
}

