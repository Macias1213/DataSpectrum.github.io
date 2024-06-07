<?php
session_start();

$usuario_o_correo = filter_input(INPUT_POST, 'usuario_o_correo', FILTER_SANITIZE_STRING);
$contrasena = $_POST['contraseña'];

$conn = new mysqli("localhost", "root", "1234567890", "dataspectrum");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM usuarios WHERE usuario = ? OR correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $usuario_o_correo, $usuario_o_correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    if (password_verify($contrasena, $fila['contrasena'])) {
        $_SESSION['usuario'] = $fila['usuario'];
        header('Location: informacion_siembras.php');
        exit();
    } else {
        $error = "Error al iniciar sesión. Credenciales incorrectas.";
    }
} else {
    $error = "Error al iniciar sesión. Credenciales incorrectas.";
}

$stmt->close();
$conn->close();

if (isset($error)) {
    header('Location: login.php?error=' . urlencode($error));
    exit();
}

// Establecer cabeceras de seguridad
header('X-XSS-Protection: 1; mode=block');
header('X-Frame-Options: SAMEORIGIN');
header('Content-Security-Policy: default-src \'self\'');

// Configurar cookies seguras
setcookie('nombre_cookie', 'valor', [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
?>