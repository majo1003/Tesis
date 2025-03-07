document.addEventListener("DOMContentLoaded", function () {
    const links = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section:not(#menu-lateral)'); // Excluir "menu-lateral"
    const footer = document.querySelector('footer'); // Seleccionar el footer

    // Ocultar todas las secciones excepto el menú lateral
    sections.forEach(section => {
        section.style.display = 'none';
    });

    // Mostrar la primera sección por defecto (por ejemplo, "calendar")
    document.getElementById('calendar').style.display = ''; 

    // Asegurar que el footer siempre esté visible
    if (footer) {
        footer.style.display = '';
    }

    // Manejar clics en los enlaces del menú lateral
    links.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();

            // Obtener el valor de data-target para determinar qué sección mostrar
            const target = this.getAttribute('data-target');
            console.log("Sección objetivo:", target);  // Verifica que el valor de data-target sea el correcto

            // Ocultar todas las secciones excepto el menú lateral
            sections.forEach(section => {
                section.style.display = 'none';
            });

            // Mostrar la sección correspondiente
            const targetSection = document.getElementById(target);
            if (targetSection) {
                targetSection.style.display = '';  // Mostrar la sección correspondiente
                console.log(`Sección ${target} mostrada.`);
            } else {
                console.log("Sección no encontrada:", target);  // Si no se encuentra la sección, mostrará un error
            }

            // Asegurar que el footer siempre esté visible
            if (footer) {
                footer.style.display = '';
            }
        });
    });

    // Manejar clics en los botones "Ver tareas" dentro de la tabla de pacientes
    const tablaPacientesBody = document.getElementById('tabla-pacientes-body');
    
    if (tablaPacientesBody) {
        tablaPacientesBody.addEventListener('click', function (e) {
            if (e.target && e.target.classList.contains('nav-link')) {
                const target = e.target.getAttribute('data-target');
                
                // Mostrar la sección de tareas
                if (target) {
                    // Ocultar todas las secciones excepto el menú lateral
                    sections.forEach(section => {
                        section.style.display = 'none';
                    });

                    // Mostrar la sección de "tasks"
                    const targetSection = document.getElementById(target);
                    if (targetSection) {
                        targetSection.style.display = '';  // Mostrar la sección de tareas
                        console.log(`Sección ${target} mostrada desde la tabla de pacientes.`);
                    } else {
                        console.log("Sección de tareas no encontrada.");
                    }

                    // Asegurar que el footer siempre esté visible
                    if (footer) {
                        footer.style.display = '';
                    }
                }
            }
        });
    }
});
