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
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=2'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <!-- Added comprehensive meta tags for SEO and social sharing -->
    <meta name="description" content="Página no encontrada. La página que buscas no existe o ha sido movida. Vuelve al inicio del Torneo de Voley VIT 2025.">
    <meta name="keywords" content="error 404, página no encontrada, torneo voley, VIT">
    <meta name="author" content="Capria Franco">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Error 404 - Página no encontrada">
    <meta property="og:description" content="La página que buscas no existe o ha sido movida.">
    <meta property="og:image" content="<?php echo buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Error 404 - Página no encontrada">
    <meta name="twitter:description" content="La página que buscas no existe o ha sido movida.">
    <meta name="twitter:image" content="<?php echo buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
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