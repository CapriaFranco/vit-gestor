<?php
    require_once __DIR__ . '/../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error 403 - Acceso Prohibido - Torneo de Voley - Edición 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=2'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <meta name="description" content="Acceso prohibido. No tienes permisos para acceder a esta página del Torneo de Voley VIT 2025.">
    <meta name="keywords" content="error 403, acceso prohibido, torneo voley, VIT">
    <meta name="author" content="VIT - Torneo de Voley">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Error 403 - Acceso Prohibido">
    <meta property="og:description" content="No tienes permisos para acceder a esta página.">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta property="og:image:secure_url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="og:image:alt" content="Logo del Torneo de Voley VIT 2025">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:site_name" content="Torneo de Voley VIT">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Error 403 - Acceso Prohibido">
    <meta name="twitter:description" content="No tienes permisos para acceder a esta página.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta name="twitter:image:alt" content="Logo del Torneo de Voley VIT 2025">
</head>
<body>
    <main>
        <div class="error-container">
            <div class="error-icon">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="Error" loading="lazy" decoding="async">
            </div>
            
            <h1>403</h1>
            <h2>Acceso Prohibido</h2>
            
            <p>Lo sentimos, no tienes permisos para acceder a esta página.</p>
            
            <a href="<?php echo buildPath($base_path, ''); ?>" class="home-button">Volver al inicio</a>
        </div>
    </main>
</body>
</html>
