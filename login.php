<!DOCTYPE html>
<html>
<head>
    <title>Iniciar sesión - DataSpectrum</title>
    <link rel="stylesheet" href="styles-general.css">
    <link rel="stylesheet" href="stylesLogin.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&display=swap" rel="stylesheet">
    <script>
        function togglePasswordVisibility(inputId) {
            var passwordInput = document.getElementById(inputId);
            var toggleIcon = document.getElementById(inputId + 'ToggleIcon');
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header>
        <a href="index.php"><button>Regresar</button></a>
        <h1>Iniciar sesión</h1>
    </header>
    <main class="container">
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        <form method="post" action="procesar_login.php">
            <label for="usuario_o_correo">Usuario o correo electrónico:</label>
            <input type="text" id="usuario_o_correo" name="usuario_o_correo" required>
            <label for="contraseña">Contraseña:</label>
            <div class="password-input">
                <input type="password" id="contraseña" name="contraseña" required>
                <i class="fas fa-eye" id="contraseñaToggleIcon" onclick="togglePasswordVisibility('contraseña')"></i>
            </div>
            <input type="submit" value="Iniciar sesión">
        </form>
        <a href="crear_cuenta.php"><button>Crear cuenta</button></a>
        <a href="recuperar_contrasena.php"><button>Olvidé mi contraseña</button></a>
    </main>
</body>
</html>