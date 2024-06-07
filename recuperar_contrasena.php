<!DOCTYPE html>
<html>
<head>
    <title>Recuperar contraseña - DataSpectrum</title>
    <link rel="stylesheet" href="styles-general.css">
    <link rel="stylesheet" href="stylesLogin.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600&display=swap" rel="stylesheet">
<body>
    <header>
        <a href="index.php"><button class="back-button">Regresar</button></a>
        <h1>Recuperar contraseña</h1>
    </header>
    <main class="container">
    <main>
        <?php if (isset($_GET['error'])): ?>
            <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
        <?php endif; ?>
        <form method="post" action="cambiar_contrasena.php">
            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" required>
            <label for="nueva_contrasena">Nueva contraseña:</label>
            <input type="password" id="nueva_contrasena" name="nueva_contrasena" required>
            <label for="confirmar_contrasena">Confirmar nueva contraseña:</label>
            <input type="password" id="confirmar_contrasena" name="confirmar_contrasena" required>
            <input type="submit" value="Cambiar contraseña">
        </form>
    </main>
</body>
</html>