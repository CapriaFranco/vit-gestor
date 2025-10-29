<?php
    session_start();
    include __DIR__ . '/../../../php/db.php';
    require_once __DIR__ . '/../../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . buildPath($base_path, 'a/login'));
        exit;
    }

    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $conn->prepare("
            SELECT e.*, i.nombre as integrante_nombre, i.posicion, i.id as integrante_id,
                   c.codigo as codigo_usado
            FROM equipos e 
            LEFT JOIN integrantes i ON e.id = i.id_equipo 
            LEFT JOIN codigos_acceso c ON e.id = c.id_equipo
            ORDER BY e.curso, e.division, e.id, i.id
        ");
        $stmt->execute();
        $equipos_all = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
        
    } catch(PDOException $e) {
        $equipos_basico = [];
        $equipos_superior = [];
        $error = "Error al conectar con la base de datos: " . $e->getMessage();
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipos - Admin VIT 2025</title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=7'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
</head>
<body>
    <main>
        <div class="form admin-teams-editor">
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Editar Equipos</h1>
                <p>Panel de Administración</p>
            </div>

            <!-- Navigation buttons with grid layout -->
            <div class="admin-nav-buttons">
                <a href="<?php echo buildPath($base_path, 'a/dash'); ?>" class="btn btn-secondary btn-full-width">← Dashboard</a>
                <a href="<?php echo buildPath($base_path, 'a/codes'); ?>" class="btn btn-secondary btn-full-width">Códigos</a>
            </div>

            <?php if (isset($error)): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error); ?></p>
                </div>
            <?php else: ?>
                
                <!-- Ciclo Básico teams -->
                <section class="cycle-section">
                    <h2 class="section-title">Ciclos Básicos</h2>
                    
                    <?php if (empty($equipos_basico)): ?>
                        <div class="empty-state">
                            <p>No hay equipos registrados en ciclos básicos aún.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($equipos_basico as $equipo_data): ?>
                            <?php $equipo = $equipo_data['equipo']; ?>
                            <div class="team-card" id="team-<?php echo $equipo['id']; ?>">
                                <div class="team-header">
                                    <h3><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h3>
                                    <a href="<?php echo buildPath($base_path, 'a/teams/edit?id=' . $equipo['id']); ?>" class="btn btn-edit btn-full-width">
                                        Editar
                                    </a>
                                </div>
                                
                                <!-- Grid layout for metadata with additional fields -->
                                <div class="team-meta">
                                    <span class="meta-item">
                                        <strong>ID:</strong> <?php echo htmlspecialchars($equipo['id']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Curso:</strong> <?php echo htmlspecialchars($equipo['curso']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>División:</strong> <?php echo htmlspecialchars($equipo['division']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Sistema:</strong> <?php echo htmlspecialchars($equipo['sistema_juego']); ?>
                                    </span>
                                    <?php if ($equipo['sistema_juego'] == '4:2' && $equipo['tipo_cuatro_dos']): ?>
                                        <span class="meta-item">
                                            <strong>Tipo:</strong> <?php echo $equipo['tipo_cuatro_dos'] == 'c' ? 'Con Centrales' : 'Con Opuestos'; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="meta-item">
                                        <strong>Color:</strong> <?php echo htmlspecialchars($equipo['color_remera']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Teléfono:</strong> <?php echo htmlspecialchars($equipo['telefono']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Código:</strong> <?php echo htmlspecialchars($equipo['codigo_usado'] ?? 'N/A'); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($equipo['fecha_registro'])); ?>
                                    </span>
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
                
                <!-- Ciclo Superior teams -->
                <section class="cycle-section">
                    <h2 class="section-title">Ciclo Superior</h2>
                    
                    <?php if (empty($equipos_superior)): ?>
                        <div class="empty-state">
                            <p>No hay equipos registrados en ciclo superior aún.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($equipos_superior as $equipo_data): ?>
                            <?php $equipo = $equipo_data['equipo']; ?>
                            <div class="team-card" id="team-<?php echo $equipo['id']; ?>">
                                <div class="team-header">
                                    <h3><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></h3>
                                    <a href="<?php echo buildPath($base_path, 'a/teams/edit?id=' . $equipo['id']); ?>" class="btn btn-edit btn-full-width">
                                        Editar
                                    </a>
                                </div>
                                
                                <div class="team-meta">
                                    <span class="meta-item">
                                        <strong>ID:</strong> <?php echo htmlspecialchars($equipo['id']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Curso:</strong> <?php echo htmlspecialchars($equipo['curso']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>División:</strong> <?php echo htmlspecialchars($equipo['division']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Sistema:</strong> <?php echo htmlspecialchars($equipo['sistema_juego']); ?>
                                    </span>
                                    <?php if ($equipo['sistema_juego'] == '4:2' && $equipo['tipo_cuatro_dos']): ?>
                                        <span class="meta-item">
                                            <strong>Tipo:</strong> <?php echo $equipo['tipo_cuatro_dos'] == 'c' ? 'Con Centrales' : 'Con Opuestos'; ?>
                                        </span>
                                    <?php endif; ?>
                                    <span class="meta-item">
                                        <strong>Color:</strong> <?php echo htmlspecialchars($equipo['color_remera']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Teléfono:</strong> <?php echo htmlspecialchars($equipo['telefono']); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Código:</strong> <?php echo htmlspecialchars($equipo['codigo_usado'] ?? 'N/A'); ?>
                                    </span>
                                    <span class="meta-item">
                                        <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($equipo['fecha_registro'])); ?>
                                    </span>
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

            <?php endif; ?>

            <div class="bottom-actions">
                <button onclick="window.scrollTo({top: 0, behavior: 'smooth'})" class="btn btn-outline">
                    ↑ Subir
                </button>
                <a href="<?php echo buildPath($base_path, 'a/dash'); ?>" class="btn btn-secondary">← Volver</a>
            </div>
        </div>
    </main>
</body>
</html>
