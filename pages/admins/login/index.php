<?php
    session_start();
    include __DIR__ . '/../../../php/db.php';
    require_once __DIR__ . '/../../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    $error = '';
    
    // Check if already logged in
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        header('Location: ' . buildPath($base_path, 'a/dash'));
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        
        $stmt = $db->prepare("SELECT password_hash FROM admin WHERE id = 1");
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            
            if (password_verify($password, $row['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                header('Location: ' . buildPath($base_path, 'a/dash'));
                exit;
            } else {
                $error = 'Contraseña incorrecta';
            }
        } else {
            $error = 'Error de configuración del sistema';
        }
        
        $stmt->close();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Torneo de Voley VIT 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=3'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
</head>
<body>
    <main>
        <form method="POST" class="form admin-login-form">
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Panel de Administración</h1>
                <p>Torneo de Voley 2025</p>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php endif; ?>

            <div class="flex-col">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/shield-question-mark.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Contraseña
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="password" name="password" class="admin-password" placeholder="Ingrese la contraseña" required autofocus>
            </div>

            <button type="submit">Ingresar</button>

            <div class="teams-link-container">
                <p><a href="<?php echo buildPath($base_path, ''); ?>" class="btn btn-outline">Volver al inicio</a></p>
            </div>
        </form>
    </main>
</body>
</html>
