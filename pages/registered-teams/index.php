<?php
    session_start();

    require_once __DIR__ . '/../../php/functions.php';
    require_once __DIR__ . '/../../php/db.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/');

    // Obtener equipos de la base de datos
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Obtener equipos del ciclo básico (1ro a 3ro)
        $stmt_basico = $conn->prepare("
            SELECT e.*, i.nombre as integrante_nombre, i.posicion, i.id as integrante_id
            FROM equipos e 
            LEFT JOIN integrantes i ON e.id = i.id_equipo 
            WHERE e.curso IN ('1ro', '2do', '3ro')
            ORDER BY e.curso, e.division, e.id, i.id
        ");
        $stmt_basico->execute();
        $equipos_basico = $stmt_basico->fetchAll(PDO::FETCH_ASSOC);
        
        // Obtener equipos del ciclo superior (4to a 7mo)
        $stmt_superior = $conn->prepare("
            SELECT e.*, i.nombre as integrante_nombre, i.posicion, i.id as integrante_id
            FROM equipos e 
            LEFT JOIN integrantes i ON e.id = i.id_equipo 
            WHERE e.curso IN ('4to', '5to', '6to', '7mo')
            ORDER BY e.curso, e.division, e.id, i.id
        ");
        $stmt_superior->execute();
        $equipos_superior = $stmt_superior->fetchAll(PDO::FETCH_ASSOC);
        
        // Agrupar equipos por ID
        $equipos_basico_grouped = [];
        $equipos_superior_grouped = [];
        
        foreach ($equipos_basico as $row) {
            if (!isset($equipos_basico_grouped[$row['id']])) {
                $equipos_basico_grouped[$row['id']]['equipo'] = $row;
                $equipos_basico_grouped[$row['id']]['integrantes'] = [];
            }
            if ($row['integrante_nombre']) {
                $equipos_basico_grouped[$row['id']]['integrantes'][] = [
                    'nombre' => $row['integrante_nombre'],
                    'posicion' => $row['posicion'],
                    'integrante_id' => $row['integrante_id']
                ];
            }
        }
        
        foreach ($equipos_superior as $row) {
            if (!isset($equipos_superior_grouped[$row['id']])) {
                $equipos_superior_grouped[$row['id']]['equipo'] = $row;
                $equipos_superior_grouped[$row['id']]['integrantes'] = [];
            }
            if ($row['integrante_nombre']) {
                $equipos_superior_grouped[$row['id']]['integrantes'][] = [
                    'nombre' => $row['integrante_nombre'],
                    'posicion' => $row['posicion'],
                    'integrante_id' => $row['integrante_id']
                ];
            }
        }
        
        // Contar totales
        $total_equipos = count($equipos_basico_grouped) + count($equipos_superior_grouped);
        $total_personas = 0;
        foreach ($equipos_basico_grouped as $equipo_data) {
            if (isset($equipo_data['integrantes']) && is_array($equipo_data['integrantes'])) {
                $total_personas += count($equipo_data['integrantes']);
            }
        }
        foreach ($equipos_superior_grouped as $equipo_data) {
            if (isset($equipo_data['integrantes']) && is_array($equipo_data['integrantes'])) {
                $total_personas += count($equipo_data['integrantes']);
            }
        }
        
    } catch(PDOException $e) {
        $equipos_basico_grouped = [];
        $equipos_superior_grouped = [];
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
    <title>Equipos Registrados - Torneo de Voley 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
</head>
<body>
    <main>
        <!-- Using form class container to match registration page styling -->
        <div class="form">
            <!-- Added form-header with logo, title, and description -->
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Torneo de Voley 2025</h1>
                <p>Equipos ya registrados</p>
            </div>

            <!-- Added back button at the top -->
            <a href="<?php echo buildPath($base_path, ''); ?>" class="btn btn-secondary" style="margin-bottom: 1.5rem;">← Volver al registro</a>
            
            <!-- Added legend/indicators section -->
            <div class="infoIcons flex-row" style="margin-bottom: 2rem;">
                <div class="infoIcon flex-row">
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/info.svg'); ?>" alt="Campo obligatorio icon" class="icon" loading="lazy" decoding="async">
                    <p>= Campo obligatorio</p>
                </div>
                <div class="infoIcon flex-row">
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" alt="Sin posicion icon" class="icon" loading="lazy" decoding="async">
                    <p>= Sin posición</p>
                </div>
                <div class="infoIcon flex-row">
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/refresh-ccw.svg'); ?>" alt="Suplente icon" class="icon" loading="lazy" decoding="async">
                    <p>= Suplente</p>
                </div>
                <div class="infoIcon flex-row">
                    <p><span class="badge-capitan">(C)</span> = Capitán</p>
                </div>
            </div>
        
            <?php if (isset($error)): ?>
                <div class="no-teams">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php else: ?>
                
                <!-- Ciclo Básico section with subtitle -->
                <section class="cycle-section">
                    <h2 class="section-title">Ciclos Básicos</h2>
                    
                    <?php if (empty($equipos_basico_grouped)): ?>
                        <div class="empty-state">
                            <p>No hay equipos registrados en ciclos básicos aún.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($equipos_basico_grouped as $equipo_data): ?>
                            <?php $equipo = $equipo_data['equipo']; ?>
                            <div class="team-card">
                                <div class="team-header">
                                    <h3><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h3>
                                    <div class="team-meta">
                                        <span class="meta-item">
                                            <strong>Curso:</strong> <?php echo htmlspecialchars($equipo['curso']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>División:</strong> <?php echo htmlspecialchars($equipo['division']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>Sistema:</strong> <?php echo htmlspecialchars($equipo['sistema_juego']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>Color:</strong> <?php echo htmlspecialchars($equipo['color_remera']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="table-wrapper">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Pos.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $numero = 1;
                                            foreach ($equipo_data['integrantes'] as $integrante): 
                                            ?>
                                                <tr>
                                                    <td><?php echo $numero; ?></td>
                                                    <td>
                                                        <?php echo htmlspecialchars($integrante['nombre']); ?>
                                                        <?php if ($numero == 1): ?>
                                                            <span class="badge-capitan">(C)</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($equipo['sistema_juego'] == '6:0'): ?>
                                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" 
                                                                 alt="Sin posición" class="icon" title="Sin posición - Sistema 6:0" loading="lazy" decoding="async">
                                                        <?php elseif ($integrante['posicion']): ?>
                                                            <?php echo htmlspecialchars($integrante['posicion']); ?>
                                                        <?php else: ?>
                                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/refresh-ccw.svg'); ?>" 
                                                                 alt="Suplente" class="icon" title="Suplente" loading="lazy" decoding="async">
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php 
                                            $numero++;
                                            endforeach; 
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>
                
                <!-- Ciclo Superior section with subtitle -->
                <section class="cycle-section">
                    <h2 class="section-title">Ciclo Superior</h2>
                    
                    <?php if (empty($equipos_superior_grouped)): ?>
                        <div class="empty-state">
                            <p>No hay equipos registrados en ciclo superior aún.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($equipos_superior_grouped as $equipo_data): ?>
                            <?php $equipo = $equipo_data['equipo']; ?>
                            <div class="team-card">
                                <div class="team-header">
                                    <h3><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h3>
                                    <div class="team-meta">
                                        <span class="meta-item">
                                            <strong>Curso:</strong> <?php echo htmlspecialchars($equipo['curso']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>División:</strong> <?php echo htmlspecialchars($equipo['division']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>Sistema:</strong> <?php echo htmlspecialchars($equipo['sistema_juego']); ?>
                                        </span>
                                        <span class="meta-item">
                                            <strong>Color:</strong> <?php echo htmlspecialchars($equipo['color_remera']); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="table-wrapper">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nombre</th>
                                                <th>Pos.</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $numero = 1;
                                            foreach ($equipo_data['integrantes'] as $integrante): 
                                            ?>
                                                <tr>
                                                    <td><?php echo $numero; ?></td>
                                                    <td>
                                                        <?php echo htmlspecialchars($integrante['nombre']); ?>
                                                        <?php if ($numero == 1): ?>
                                                            <span class="badge-capitan">(C)</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php if ($equipo['sistema_juego'] == '6:0'): ?>
                                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" 
                                                                 alt="Sin posición" class="icon" title="Sin posición - Sistema 6:0" loading="lazy" decoding="async">
                                                        <?php elseif ($integrante['posicion']): ?>
                                                            <?php echo htmlspecialchars($integrante['posicion']); ?>
                                                        <?php else: ?>
                                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/refresh-ccw.svg'); ?>" 
                                                                 alt="Suplente" class="icon" title="Suplente" loading="lazy" decoding="async">
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php 
                                            $numero++;
                                            endforeach; 
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </section>
                
                <!-- Added counters section -->
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
                
            <?php endif; ?>

            <!-- Added bottom navigation buttons -->
            <div class="bottom-actions">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="btn btn-outline">
                    ↑ Subir
                </button>
                <a href="<?php echo buildPath($base_path, ''); ?>" class="btn btn-secondary">
                    ← Volver
                </a>
            </div>
        </div>
    </main>
</body>
</html>
