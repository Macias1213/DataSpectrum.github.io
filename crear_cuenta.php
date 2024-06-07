<!DOCTYPE html>
<html>
<head>
    <title>Crear cuenta - DataSpectrum</title>
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
        <h1>Crear Cuenta</h1>
    </header>
    <main class="container">
        <form method="post" action="registrar_usuario.php">
            <label for="nuevo_usuario">Nuevo usuario:</label>
            <input type="text" id="nuevo_usuario" name="nuevo_usuario" required>
            <label for="nuevo_correo">Correo electrónico:</label>
            <input type="email" id="nuevo_correo" name="nuevo_correo" required>
            <label for="nueva_contraseña">Nueva contraseña:</label>
            <div class="password-input">
                <input type="password" id="nueva_contraseña" name="nueva_contraseña" required>
                <i class="fas fa-eye" id="nueva_contraseñaToggleIcon" onclick="togglePasswordVisibility('nueva_contraseña')"></i>
            </div>
            <p class="requisitos-contrasena">La contraseña debe tener al menos 8 caracteres, aquí incluidos una mayúscula, una minúscula, un número y un carácter especial.</p>
            <input type="submit" value="Crear cuenta">
        </form>
    </main>
</body>
</html>