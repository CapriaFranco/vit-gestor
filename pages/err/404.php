<?php
    require_once __DIR__ . '/../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 404 - Página no encontrada - Torneo de Voley - Edición 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
</head>
<body>
    <main>
        <div class="error-container">
            <div class="error-icon">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="Error" loading="lazy" decoding="async">
            </div>
            
            <h1>404</h1>
            <h2>Página no encontrada</h2>
            
            <p>Lo sentimos, la página que estás buscando no existe o ha sido movida.</p>
            
            <a href="<?php echo buildPath($base_path, ''); ?>" class="home-button">Volver al inicio</a>
        </div>
    </main>
</body>
</html>