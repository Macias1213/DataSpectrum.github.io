<?php
$correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);
$nueva_contrasena = $_POST['nueva_contrasena'];
$confirmar_contrasena = $_POST['confirmar_contrasena'];

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $error = 'Correo electrónico inválido.';
    header('Location: recuperar_contrasena.php?error=' . urlencode($error));
    exit();
}

if ($nueva_contrasena !== $confirmar_contrasena) {
    $error = 'Las contraseñas no coinciden.';
    header('Location: recuperar_contrasena.php?error=' . urlencode($error));
    exit();
}

$conn = new mysqli("localhost", "root", "1234567890", "dataspectrum");
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

$patron_contrasena = '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9]).{8,}$/';
if (!preg_match($patron_contrasena, $nueva_contrasena)) {
    $error = 'La contraseña no cumple con los requisitos.';
    header('Location: recuperar_contrasena.php?error=' . urlencode($error));
    exit();
}

$nueva_contrasena_cifrada = password_hash($nueva_contrasena, PASSWORD_DEFAULT);

$sql = "SELECT * FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $sql_update = "UPDATE usuarios SET contrasena = ? WHERE correo = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ss", $nueva_contrasena_cifrada, $correo);

    if ($stmt_update->execute()) {
        header('Location: contrasena_cambiada.php');
        exit();
    } else {
        $error = "Error al cambiar la contraseña: " . $conn->error;
        header('Location: recuperar_contrasena.php?error=' . urlencode($error));
        exit();
    }
} else {
    $error = 'El correo electrónico no está registrado.';
    header('Location: recuperar_contrasena.php?error=' . urlencode($error));
    exit();
}

$stmt->close();
$stmt_update->close();
$conn->close();
?>