<?php
    session_start();
    include __DIR__ . '/../../../php/db.php';
    require_once __DIR__ . '/../../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    // Check if logged in
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . buildPath($base_path, 'a/login'));
        exit;
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Get all teams with integrantes
        $stmt = $conn->prepare("
            SELECT e.*, i.nombre as integrante_nombre, i.posicion, i.id as integrante_id
            FROM equipos e 
            LEFT JOIN integrantes i ON e.id = i.id_equipo 
            ORDER BY e.curso, e.division, e.id, i.id
        ");
        $stmt->execute();
        $equipos_all = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Group teams by ID
        $equipos_grouped = [];
        foreach ($equipos_all as $row) {
            if (!isset($equipos_grouped[$row['id']])) {
                $equipos_grouped[$row['id']]['equipo'] = $row;
                $equipos_grouped[$row['id']]['integrantes'] = [];
            }
            if ($row['integrante_nombre']) {
                $equipos_grouped[$row['id']]['integrantes'][] = [
                    'nombre' => $row['integrante_nombre'],
                    'posicion' => $row['posicion'],
                    'integrante_id' => $row['integrante_id']
                ];
            }
        }
        
        // Separate by cycle
        $equipos_basico = [];
        $equipos_superior = [];
        foreach ($equipos_grouped as $equipo_data) {
            $curso = $equipo_data['equipo']['curso'];
            if (in_array($curso, ['1ro', '2do', '3ro'])) {
                $equipos_basico[] = $equipo_data;
            } else {
                $equipos_superior[] = $equipo_data;
            }
        }
        
        // Get colors used by cycle
        $stmt_colores_basico = $conn->prepare("
            SELECT DISTINCT color_remera 
            FROM equipos 
            WHERE curso IN ('1ro', '2do', '3ro')
            ORDER BY color_remera
        ");
        $stmt_colores_basico->execute();
        $colores_basico = $stmt_colores_basico->fetchAll(PDO::FETCH_COLUMN);
        
        $stmt_colores_superior = $conn->prepare("
            SELECT DISTINCT color_remera 
            FROM equipos 
            WHERE curso IN ('4to', '5to', '6to', '7mo')
            ORDER BY color_remera
        ");
        $stmt_colores_superior->execute();
        $colores_superior = $stmt_colores_superior->fetchAll(PDO::FETCH_COLUMN);
        
        // Count totals
        $total_equipos = count($equipos_grouped);
        $total_personas = 0;
        foreach ($equipos_grouped as $equipo_data) {
            $total_personas += count($equipo_data['integrantes']);
        }
        
    } catch(PDOException $e) {
        $equipos_basico = [];
        $equipos_superior = [];
        $colores_basico = [];
        $colores_superior = [];
        $total_equipos = 0;
        $total_personas = 0;
        $error = "Error al conectar con la base de datos: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Admin VIT 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=5'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
</head>
<body>
    <main>
        <div class="form admin-dashboard">
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Dashboard</h1>
                <p>Panel de Administración</p>
            </div>

            <!-- Navigation buttons -->
            <!-- Updated to use grid layout for better responsive design -->
            <div class="admin-nav-buttons">
                <a href="<?php echo buildPath($base_path, ''); ?>" class="btn btn-secondary">Inicio</a>
                <a href="<?php echo buildPath($base_path, 'teams'); ?>" class="btn btn-secondary">Ver Equipos</a>
                <a href="<?php echo buildPath($base_path, 'a/codes'); ?>" class="btn btn-secondary">Códigos</a>
                <a href="<?php echo buildPath($base_path, 'a/teams'); ?>" class="btn btn-secondary">Editar Equipos</a>
            </div>

            <!-- Stats counters -->
            <div class="stats-container">
                <div class="stat-item">
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/shield-question-mark.svg'); ?>" alt="" class="stat-icon" loading="lazy" decoding="async">
                    <div>
                        <p class="stat-number"><?php echo $total_equipos; ?></p>
                        <p class="stat-label">Equipos registrados</p>
                    </div>
                </div>
                <div class="stat-item">
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/users-round.svg'); ?>" alt="" class="stat-icon" loading="lazy" decoding="async">
                    <div>
                        <p class="stat-number"><?php echo $total_personas; ?></p>
                        <p class="stat-label">Personas registradas</p>
                    </div>
                </div>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php else: ?>
                
                <!-- Colors used section with updated styling -->
                <section class="colors-section">
                    <h2 class="section-title">Colores Utilizados</h2>
                    
                    <div class="colors-grid">
                        <!-- Ciclo Básico colors -->
                        <div class="color-card">
                            <h3>Ciclos Básicos</h3>
                            <?php if (empty($colores_basico)): ?>
                                <p class="text-muted">No hay colores registrados</p>
                            <?php else: ?>
                                <ul class="color-list">
                                    <?php foreach ($colores_basico as $color): ?>
                                        <li><?php echo htmlspecialchars($color); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Ciclo Superior colors -->
                        <div class="color-card">
                            <h3>Ciclo Superior</h3>
                            <?php if (empty($colores_superior)): ?>
                                <p class="text-muted">No hay colores registrados</p>
                            <?php else: ?>
                                <ul class="color-list">
                                    <?php foreach ($colores_superior as $color): ?>
                                        <li><?php echo htmlspecialchars($color); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
                
            <?php endif; ?>

            <div class="bottom-actions">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="btn btn-outline">
                    ↑ Subir
                </button>
                <a href="<?php echo buildPath($base_path, 'php/logout.php'); ?>" class="btn btn-outline">Cerrar Sesión</a>
            </div>
        </div>
    </main>
</body>
</html>
