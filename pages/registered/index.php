<?php
    session_start();

    require_once __DIR__ . '/../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!isset($_SESSION['registro_exitoso'])) {
        header('Location: ' . buildPath($base_path, ''));
        exit;
    }

    // Si ya se mostró la página de éxito, limpiar la sesión y redirigir
    if (isset($_SESSION['registro_mostrado'])) {
        unset($_SESSION['registro_exitoso']);
        unset($_SESSION['registro_mostrado']);
        header('Location: ' . buildPath($base_path, ''));
        exit;
    }

    $_SESSION['registro_mostrado'] = true;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Exitoso - Torneo de Voley - Edición 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
</head>
<body>
    <main>
        <div class="success-container">
            <div class="success-icon">
                <img src="<?php echo buildPath($base_path, 'assets/img/icons/volleyball.svg'); ?>" alt="Success" loading="lazy" decoding="async">
            </div>
            
            <h1>¡Registro Exitoso!</h1>
            
            <p>Su equipo se ha registrado exitosamente en el Torneo de Voley 2025.</p>
            
            <p>Para mantenerse informado sobre el torneo, únase a nuestro grupo de WhatsApp:</p>
            
            <a href="https://chat.whatsapp.com/CbpE4tkN2kMAoaTlkQXSLr?mode=wwt" target="_blank" class="whatsapp-button">
                <img src="<?php echo buildPath($base_path, 'assets/img/icons/whatsapp.svg'); ?>" alt="WhatsApp" loading="lazy" decoding="async">
                Unirse al grupo
            </a>
            
            <div class="note">
                <strong>Nota:</strong> Para realizar cambios en su registro, por favor contacte directamente con los organizadores del torneo.
            </div>
        </div>
    </main>
    
    <script>
        // Prevent back button from showing cached form data
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.href = '<?php echo buildPath($base_path, ''); ?>';
            }
        });
        
        setTimeout(() => {
            fetch('<?php echo buildPath($base_path, 'php/clear_session.php'); ?>');
        }, 2000);
    </script>
</body>
</html>
