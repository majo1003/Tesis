<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <title>Registrar Ahora</title>
</head>

<body>
    <main class="main-registrarCuenta">
        <section id="registrar-cuenta">
            <div class="container">
                <div class="left-panel">
                    <img src="images/icon-registrarCuenta.svg" alt="Icono de presentación" class="icon">
                </div>
                <div class="right-panel">
                    <h1>Crear cuenta</h1>
                    <form action="BD/registrar.php" method="POST" class="register-form">
                        <label for="nombre">Nombres completos</label>
                        <input type="text" id="nombre" name="nombre" required>

                        <label for="apellido">Apellido</label>
                        <input type="text" id="apellido" name="apellido" required>

                        <label for="correo">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" required>

                        <label for="password">Contraseña</label>
                        <div class="password-container">
                            <input type="password" id="password" name="password" class="password-input" required>
                            <span class="toggle-password" data-target="password">
                                <img 
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAd9JREFUSEvt1EuojlEUBuDnoCShKEkRynVCmGBCDMiAkWsM5DJkRklnYGKGYkKZIDFzyUhyiwFSysQtSbkMKKTc7VX76Du77z979M/OV3+7f++117ved71r9+jy19Pl/AYBqgrXJJqIjViNyZiQM77Da1zBGXzohNQJYCwOYldah1bK/IXjOIAvZWwbwGxcQ1T/I60ncA/P8i9yTMcMLMHOXMRLLMvM/uOUAFPwAONwF1vxosJgZgI8iwV4k2RbiPd9d5oAw5LW9zEPt7ACP3PgNBzDSnxN/TiFPQ3gEZnl3MTiJpa2AezFIbzCfHxqJLiKVQWTowVImOARon/B/HTENxmEhlNT0JoEcKlI9qeIjeNo6OgibjtO4mGWqh/AE8zB+rReKC5+xqhirw1gc7btY4Rc/QA24FxuVBx+bCQ8kiTbXZEoXBcSjcdaXCwBhqTG3sEi3M6W+12AbMv/yyYPz66L3l1P95e3NTn2xuQZCKsF2Jbc9IGc2rTpjTz13zoBxP7I5OnLmUEMWkxp2PcpniPsHLadhcXYkQctjLEO35vVdHoq4nnoTXOwHyHdQF8UsS+xP9wWVHvsJqXKN+UB63vs/qY36m2WLpieb05uCVIDqBRfPx4EqGrUdYn+AW7RVxkKyEc6AAAAAElFTkSuQmCC" />
                            </span>
                        </div>

                        <label for="confirm-password">Confirmar contraseña</label>
                        <div class="password-container">
                            <input type="password" id="confirm-password" name="confirm-password" class="password-input" required>
                            <span class="toggle-password" data-target="confirm-password">
                                <img 
                                src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAd9JREFUSEvt1EuojlEUBuDnoCShKEkRynVCmGBCDMiAkWsM5DJkRklnYGKGYkKZIDFzyUhyiwFSysQtSbkMKKTc7VX76Du77z979M/OV3+7f++117ved71r9+jy19Pl/AYBqgrXJJqIjViNyZiQM77Da1zBGXzohNQJYCwOYldah1bK/IXjOIAvZWwbwGxcQ1T/I60ncA/P8i9yTMcMLMHOXMRLLMvM/uOUAFPwAONwF1vxosJgZgI8iwV4k2RbiPd9d5oAw5LW9zEPt7ACP3PgNBzDSnxN/TiFPQ3gEZnl3MTiJpa2AezFIbzCfHxqJLiKVQWTowVImOARon/B/HTENxmEhlNT0JoEcKlI9qeIjeNo6OgibjtO4mGWqh/AE8zB+rReKC5+xqhirw1gc7btY4Rc/QA24FxuVBx+bCQ8kiTbXZEoXBcSjcdaXCwBhqTG3sEi3M6W+12AbMv/yyYPz66L3l1P95e3NTn2xuQZCKsF2Jbc9IGc2rTpjTz13zoBxP7I5OnLmUEMWkxp2PcpniPsHLadhcXYkQctjLEO35vVdHoq4nnoTXOwHyHdQF8UsS+xP9wWVHvsJqXKN+UB63vs/qY36m2WLpieb05uCVIDqBRfPx4EqGrUdYn+AW7RVxkKyEc6AAAAAElFTkSuQmCC" />
                            </span>
                        </div>

                        <button type="submit">Registro</button>
                    </form>
                    <p class="login-link">
                        ¿Ya tienes una cuenta? <a href="index.php">Iniciar Sesión</a>
                    </p>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Script para mostrar y ocultar contraseñas
        document.querySelectorAll('.toggle-password').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const passwordInput = document.getElementById(this.getAttribute('data-target'));
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAmNJREFUSEu11curlVUYBvDfqYhuQopZEEbSRbMkIhtEpEWUFxDCQEwQAkEomtYksH9BqKBBGEE1CBo4EIKw8tJFFCMovKAiKEqGOqiR3VwPvJ98e7PPYcPZrsmG9a31Pu9zedeecp3X1HWubxjgbXyMPyYF3Ad4Ax/gKFZMCqQPcDf2YHGBPINLs2UyLNECfIul+AUvYy3W4dYhsCv4Cp/iwnSNjDJ5Pr7Do2N2/w/exzb8OXxnFMAD2FVSdedz+Rv8VhsP4WFExq24EafwAk73QYYBUnw/7qkL/yN7J3CgMXsOt5SMb1WxePYZnsQZPIGLHUgf4Cb8jMcQ2osa7f/K+AdHyPVIhSGf4s+PeLzkfX4UwJt4r1coHb+I2xr1fa3LyNJfK3EYf9XmfdXgPGwu8wcG7SzubbRfQwYuSfoJL+GO6iy699e5utPtbcFHONR8fCqbfYl+reS8Uj7sLaNDvQ8SzbuViU+0u5Xm8hJkWCPhAMCrLRmf1wTn481Nou/LizCJXGESuTpPPinGqTW3SXOyftfUjAwA3FCdP42DVXBOFby/sfihGbkKt5dcS6rTJOvfKpgkfYjXR5mcvTtbtr/GchxH5IqJiW78CUjHJM9KQHIuKVpYSYr5f08HkP10vbPFM1FLTL8ss99BEhKJNmB9PY5drQxhwM/PNGjdt0zmu62TFI1046wAPIvL4wB0Z0J7U2OwGsl5JjzTnXjmSfiiFd1djJfhSE37tcdvUv9odyGxjifH2lMSH34fjuk4Msx0JvPQzU4GbsekAVIvEm5sadw+U4pmy2Tg/qQ8mLapq1Tgchm7W6q5AAAAAElFTkSuQmCC"/>';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAAAAXNSR0IArs4c6QAAAd9JREFUSEvt1EuojlEUBuDnoCShKEkRynVCmGBCDMiAkWsM5DJkRklnYGKGYkKZIDFzyUhyiwFSysQtSbkMKKTc7VX76Du77z979M/OV3+7f++117ved71r9+jy19Pl/AYBqgrXJJqIjViNyZiQM77Da1zBGXzohNQJYCwOYldah1bK/IXjOIAvZWwbwGxcQ1T/I60ncA/P8i9yTMcMLMHOXMRLLMvM/uOUAFPwAONwF1vxosJgZgI8iwV4k2RbiPd9d5oAw5LW9zEPt7ACP3PgNBzDSnxN/TiFPQ3gEZnl3MTiJpa2AezFIbzCfHxqJLiKVQWTowVImOARon/B/HTENxmEhlNT0JoEcKlI9qeIjeNo6OgibjtO4mGWqh/AE8zB+rReKC5+xqhirw1gc7btY4Rc/QA24FxuVBx+bCQ8kiTbXZEoXBcSjcdaXCwBhqTG3sEi3M6W+12AbMv/yyYPz66L3l1P95e3NTn2xuQZCKsF2Jbc9IGc2rTpjTz13zoBxP7I5OnLmUEMWkxp2PcpniPsHLadhcXYkQctjLEO35vVdHoq4nnoTXOwHyHdQF8UsS+xP9wWVHvsJqXKN+UB63vs/qY36m2WLpieb05uCVIDqBRfPx4EqGrUdYn+AW7RVxkKyEc6AAAAAElFTkSuQmCC"/>';
                }
            });
        });
    </script>
</body>

</html>
