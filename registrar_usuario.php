<!DOCTYPE html>
<html>
<head>
    <title>Crear cuenta - DataSpectrum</title>
    <link rel="stylesheet" href="styles-general.css">
    <link rel="stylesheet" href="stylesLogin.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&display=swap" rel="stylesheet">
    <style>
        .error {
            color: red;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
        }
        .requisitos-contrasena {
            color: #555;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php"><button>Regresar</button></a>
        <h1>Crear cuenta</h1>
    </header>
    <main class="container">
        <p class="requisitos-contrasena">La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.</p>
        <?php
        $nuevo_usuario = filter_input(INPUT_POST, 'nuevo_usuario', FILTER_SANITIZE_STRING);
        $nueva_contrasena = $_POST['nueva_contraseña'] ?? '';
        $nuevo_correo = filter_input(INPUT_POST, 'nuevo_correo', FILTER_SANITIZE_EMAIL);
        $error_contrasena = '';
        $error_usuario_existente = '';
        $error_correo_existente = '';
        $error_correo_vacio = '';

        // Validar fortaleza de contraseña
        $patron_contrasena = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/';
        if (!preg_match($patron_contrasena, $nueva_contrasena)) {
            $error_contrasena = 'La contraseña no cumple con los requisitos.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error_contrasena) {
            $conn = new mysqli("localhost", "root", "1234567890", "dataspectrum");
            if ($conn->connect_error) {
                die("Error de conexión: " . $conn->connect_error);
            }

            // Verificar si el nombre de usuario ya existe
            $sql_check_usuario = "SELECT * FROM usuarios WHERE usuario = ?";
            $stmt_check_usuario = $conn->prepare($sql_check_usuario);
            $stmt_check_usuario->bind_param("s", $nuevo_usuario);
            $stmt_check_usuario->execute();
            $resultado_check_usuario = $stmt_check_usuario->get_result();

            if ($resultado_check_usuario->num_rows > 0) {
                $error_usuario_existente = 'El nombre de usuario ya está registrado. Por favor, elige otro.';
            }

            // Verificar si el correo electrónico ya existe
            $sql_check_correo = "SELECT * FROM usuarios WHERE correo = ?";
            $stmt_check_correo = $conn->prepare($sql_check_correo);
            $stmt_check_correo->bind_param("s", $nuevo_correo);
            $stmt_check_correo->execute();
            $resultado_check_correo = $stmt_check_correo->get_result();

            if ($resultado_check_correo->num_rows > 0) {
                $error_correo_existente = 'El correo electrónico ya está registrado. Por favor, elige otro.';
            }

            if (empty($nuevo_correo)) {
                $error_correo_vacio = 'El correo electrónico no puede estar vacío.';
            }

            if (!$error_usuario_existente && !$error_correo_existente && !$error_correo_vacio) {
                // Cifrar la contraseña
                $nueva_contrasena_cifrada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);
                $sql = "INSERT INTO usuarios (usuario, contrasena, correo) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $nuevo_usuario, $nueva_contrasena_cifrada, $nuevo_correo);

                if ($stmt->execute()) {
                    // Guardar usuario, correo y contraseña en el archivo usuarios.txt
                    guardarUsuarioEnArchivo($nuevo_correo, $nuevo_usuario, $nueva_contrasena);

                    header('Location: usuario_registrado.php');
                    exit();
                } else {
                    echo "Error al registrar el usuario: " . $conn->error;
                }
                $stmt->close();
            }
            $stmt_check_usuario->close();
            $stmt_check_correo->close();
            $conn->close();
        }

        // Establecer cabeceras de seguridad
        header('X-XSS-Protection: 1; mode=block');
        header('X-Frame-Options: SAMEORIGIN');
        header('Content-Security-Policy: default-src \'self\'');

        // Función para guardar usuario, correo y contraseña en el archivo usuarios.txt
        function guardarUsuarioEnArchivo($correo, $usuario, $contrasena)
        {
            $archivo = 'usuarios.txt';
            $datos = $correo . ' - ' . $usuario . ' - ' . $contrasena . PHP_EOL;
            $fileHandler = fopen($archivo, 'a') or die("No se puede abrir el archivo");
            fwrite($fileHandler, $datos);
            fclose($fileHandler);
        }
        ?>

        <?php if ($error_contrasena): ?>
            <p class="error"><?php echo $error_contrasena; ?></p>
        <?php endif; ?>
        <?php if ($error_usuario_existente): ?>
            <p class="error"><?php echo $error_usuario_existente; ?></p>
        <?php endif; ?>
        <?php if ($error_correo_existente): ?>
            <p class="error"><?php echo $error_correo_existente; ?></p>
        <?php endif; ?>
        <?php if ($error_correo_vacio): ?>
            <p class="error"><?php echo $error_correo_vacio; ?></p>
        <?php endif; ?>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="nuevo_usuario">Nuevo usuario:</label>
            <input type="text" id="nuevo_usuario" name="nuevo_usuario" value="<?php echo $nuevo_usuario; ?>" required>
            <label for="nuevo_correo">Correo electrónico:</label>
            <input type="email" id="nuevo_correo" name="nuevo_correo" value="<?php echo $nuevo_correo; ?>" required>
            <label for="nueva_contraseña">Nueva contraseña:</label>
            <input type="password" id="nueva_contraseña" name="nueva_contraseña" value="<?php echo $nueva_contrasena; ?>" required>
            <input type="submit" value="Crear cuenta">
        </form>
    </main>
</body>
</html>