document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section:not(#menu-lateral)'); // Excluir "menu-lateral"
    const footer = document.querySelector('footer'); // Seleccionar el footer

    // Ocultar todas las secciones excepto el menú lateral
    sections.forEach(section => {
        section.style.display = 'none';
    });
    document.getElementById('calendar').style.display = ''; // Mostrar la primera sección por defecto (por ejemplo, "calendar")

    // Asegurar que el footer siempre esté visible
    if (footer) {
        footer.style.display = '';
    }

    // Manejar clics en los enlaces
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const target = this.getAttribute('data-target');

            // Ocultar todas las secciones excepto el menú lateral
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Mostrar la sección correspondiente
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.style.display = '';
            }

            // Asegurar que el footer siempre esté visible
            if (footer) {
                footer.style.display = '';
            }
        });
    });
});
