<?php
    session_start();
    include __DIR__ . '/../../../php/db.php';
    require_once __DIR__ . '/../../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    // Check if logged in
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . buildPath($base_path, 'admin'));
        exit;
    }

    $message = '';
    $generated_code = '';

    // Generate code
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate'])) {
        // Generate random code: aaaa-bbbb (8 chars, a-z 0-9, lowercase, no ñ)
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        $part1 = '';
        $part2 = '';
        
        for ($i = 0; $i < 4; $i++) {
            $part1 .= $chars[random_int(0, strlen($chars) - 1)];
            $part2 .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        $generated_code = $part1 . '-' . $part2;
        
        // Save to database
        $stmt = $db->prepare("INSERT INTO codigos_acceso (codigo, usado) VALUES (?, 0)");
        $stmt->bind_param("s", $generated_code);
        
        if ($stmt->execute()) {
            // Store in session and redirect to prevent resubmission
            $_SESSION['generated_code'] = $generated_code;
            $_SESSION['code_message'] = 'Código generado exitosamente';
            header('Location: ' . buildPath($base_path, 'dash'));
            exit;
        } else {
            $message = 'Error al generar código';
            $generated_code = '';
        }
    }

    if (isset($_SESSION['generated_code'])) {
        $generated_code = $_SESSION['generated_code'];
        $message = $_SESSION['code_message'];
        unset($_SESSION['generated_code']);
        unset($_SESSION['code_message']);
    }

    // Get all codes
    $codes_query = "SELECT c.id, c.codigo, c.usado, c.id_equipo, c.fecha_creacion, e.nombre_equipo 
                    FROM codigos_acceso c 
                    LEFT JOIN equipos e ON c.id_equipo = e.id 
                    ORDER BY c.fecha_creacion DESC";
    $codes_result = $db->query($codes_query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin VIT 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=4'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
</head>
<body>
    <main>
        <div class="form admin-dashboard">
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Panel de Administración</h1>
                <p>Generador de Códigos de Acceso</p>
            </div>

            <form method="POST" class="generate-form">
                <button type="submit" name="generate" class="btn-generate">
                    Generar Nuevo Código
                </button>
            </form>

            <?php if ($message && $generated_code): ?>
                <div class="success-message-with-code">
                    <p class="success-text"><?php echo htmlspecialchars($message); ?></p>
                    <div class="code-box">
                        <span class="code-text"><?php echo htmlspecialchars($generated_code); ?></span>
                        <button type="button" class="btn-copy" onclick="copyCode('<?php echo htmlspecialchars($generated_code); ?>')">
                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/copy.svg'); ?>" alt="Copiar" class="icon">
                            Copiar
                        </button>
                    </div>
                </div>
            <?php elseif ($message): ?>
                <div class="<?php echo strpos($message, 'Error') !== false ? 'error-message' : 'success-message'; ?>">
                    <p><?php echo htmlspecialchars($message); ?></p>
                </div>
            <?php endif; ?>

            <div class="codes-list">
                <h2>Códigos Generados</h2>
                <div class="table-wrapper">
                    <table class="admin-codes-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Código</th>
                                <th>Equipo</th>
                                <th>Fecha</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($codes_result && $codes_result->num_rows > 0): ?>
                                <?php while ($code = $codes_result->fetch_assoc()): ?>
                                    <tr class="<?php echo $code['usado'] ? 'code-used' : 'code-available'; ?>">
                                        <td class="text-center"><strong>#<?php echo str_pad($code['id'], 2, '0', STR_PAD_LEFT); ?></strong></td>
                                        <td>
                                            <div class="code-with-status">
                                                <span class="status-indicator <?php echo $code['usado'] ? 'status-used' : 'status-available'; ?>"></span>
                                                <strong><?php echo htmlspecialchars($code['codigo']); ?></strong>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if ($code['id_equipo'] && $code['nombre_equipo']): ?>
                                                <span class="team-info">
                                                    <strong>#<?php echo str_pad($code['id_equipo'], 2, '0', STR_PAD_LEFT); ?></strong>
                                                    <?php echo htmlspecialchars($code['nombre_equipo']); ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($code['fecha_creacion'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="empty-state">No hay códigos generados</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bottom-actions">
                <a href="<?php echo buildPath($base_path, 'teams'); ?>" class="btn btn-secondary">Ver Equipos</a>
                <a href="<?php echo buildPath($base_path, 'php/logout.php'); ?>" class="btn btn-outline">Cerrar Sesión</a>
            </div>
        </div>
    </main>

    <script>
        function copyCode(code) {
            navigator.clipboard.writeText(code).then(() => {
                alert('Código copiado: ' + code);
            });
        }
    </script>
</body>
</html>
