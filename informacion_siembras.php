<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit();
}

$usuario = $_SESSION['usuario'];

$conn = new mysqli("localhost", "root", "1234567890", "dataspectrum");

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$sql = "SELECT * FROM usuarios WHERE usuario = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $fila = $resultado->fetch_assoc();
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Información de tus siembras</title>
        <link rel="stylesheet" href="styles-general.css">
        <link rel="stylesheet" href="stylesLogin.css">
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&display=swap" rel="stylesheet">
    </head>
    <body>
        <header>
            <a href="index.php"><button class="back-button">Regresar</button></a>
            <h1>Información de sus siembras</h1>
        </header>
        <main class="container">
        <main>
            <p>Bienvenido señor <?php echo htmlspecialchars($usuario); ?>.</p>
            <p>Aquí se mostrará la información de tus siembras proximamente.</p>
        </main>
    </body>
    </html>
    <?php
} else {
    session_destroy();
    header('Location: login.php?error=' . urlencode('Error al iniciar sesión. Credenciales incorrectas.'));
    exit();
}

$stmt->close();
$conn->close();

// Establecer cabeceras de seguridad
header('X-XSS-Protection: 1; mode=block');
header('X-Frame-Options: SAMEORIGIN');
header('Content-Security-Policy: default-src \'self\'');
?>