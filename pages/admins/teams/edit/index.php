<?php
    session_start();
    include __DIR__ . '/../../../../php/db.php';
    require_once __DIR__ . '/../../../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . buildPath($base_path, 'a/login'));
        exit;
    }

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Location: ' . buildPath($base_path, 'a/teams'));
        exit;
    }

    $team_id = intval($_GET['id']);
    $success_message = '';
    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nombre_equipo = $_POST['nombre_equipo'] ?? '';
        $color_remera = $_POST['color_remera'] ?? '';
        $curso = $_POST['curso'] ?? '';
        $division = $_POST['division'] ?? '';
        $sistema_juego = $_POST['sistema_juego'] ?? '';
        $tipo_cuatro_dos = ($sistema_juego === '4:2' && isset($_POST['tipo_cuatro_dos'])) ? $_POST['tipo_cuatro_dos'] : NULL;
        $telefono = $_POST['telefono'] ?? '';

        $stmt = $db->prepare("UPDATE equipos SET nombre_equipo=?, color_remera=?, curso=?, division=?, sistema_juego=?, tipo_cuatro_dos=?, telefono=? WHERE id=?");
        $stmt->bind_param("sssssssi", $nombre_equipo, $color_remera, $curso, $division, $sistema_juego, $tipo_cuatro_dos, $telefono, $team_id);
        
        if ($stmt->execute()) {
            $db->query("DELETE FROM integrantes WHERE id_equipo = $team_id");
            
            for ($i = 1; $i <= 8; $i++) {
                if (!empty($_POST["integrante_$i"])) {
                    $nombre = $_POST["integrante_$i"];
                    $pos = $_POST["posicion_$i"] ?? NULL;
                    
                    $stmt2 = $db->prepare("INSERT INTO integrantes (id_equipo, nombre, posicion) VALUES (?, ?, ?)");
                    $stmt2->bind_param("iss", $team_id, $nombre, $pos);
                    $stmt2->execute();
                }
            }
            
            $success_message = "Equipo actualizado correctamente";
        } else {
            $error_message = "Error al actualizar el equipo";
        }
    }

    $sql = "
        SELECT e.*, i.nombre as integrante_nombre, i.posicion, i.id as integrante_id,
               c.codigo as codigo_usado
        FROM equipos e 
        LEFT JOIN integrantes i ON e.id = i.id_equipo 
        LEFT JOIN codigos_acceso c ON e.id = c.id_equipo
        WHERE e.id = $team_id
        ORDER BY i.id
    ";
    $result = $db->query($sql);
    
    if ($result->num_rows === 0) {
        header('Location: ' . buildPath($base_path, 'a/teams'));
        exit;
    }

    $equipo = null;
    $integrantes = [];
    while ($row = $result->fetch_assoc()) {
        if (!$equipo) {
            $equipo = $row;
        }
        if ($row['integrante_nombre']) {
            $integrantes[] = [
                'nombre' => $row['integrante_nombre'],
                'posicion' => $row['posicion'],
                'integrante_id' => $row['integrante_id']
            ];
        }
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Equipo - <?php echo htmlspecialchars($equipo['nombre_equipo']); ?></title>
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=7'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
    
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#94d0af">
</head>
<body>
    <main>
        <form method="POST" id="formEditEquipo" class="form">
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Editar Equipo</h1>
                <p><?php echo htmlspecialchars($equipo['nombre_equipo']); ?></p>
            </div>

            <?php if ($success_message): ?>
                <div class="success-message">
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>

            <?php if ($error_message): ?>
                <div class="error-message">
                    <p><?php echo htmlspecialchars($error_message); ?></p>
                </div>
            <?php endif; ?>

            <!-- Team metadata display -->
            <div class="team-meta-edit">
                <span class="meta-item">
                    <strong>ID:</strong> <?php echo htmlspecialchars($equipo['id']); ?>
                </span>
                <span class="meta-item">
                    <strong>Código:</strong> <?php echo htmlspecialchars($equipo['codigo_usado'] ?? 'N/A'); ?>
                </span>
                <span class="meta-item">
                    <strong>Fecha:</strong> <?php echo date('d/m/Y', strtotime($equipo['fecha_registro'])); ?>
                </span>
            </div>

            <!-- Editable fields in grid layout -->
            <div class="curso">
                <div class="flex-col">
                    <label>
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/squares-unite.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                        Curso
                        <abbr title="Campo obligatorio">*</abbr>
                    </label>
                    <select id="curso" name="curso" required onchange="actualizarDivision()">
                        <option value="1ro" <?php echo $equipo['curso'] == '1ro' ? 'selected' : ''; ?>>1ro</option>
                        <option value="2do" <?php echo $equipo['curso'] == '2do' ? 'selected' : ''; ?>>2do</option>
                        <option value="3ro" <?php echo $equipo['curso'] == '3ro' ? 'selected' : ''; ?>>3ro</option>
                        <option value="4to" <?php echo $equipo['curso'] == '4to' ? 'selected' : ''; ?>>4to</option>
                        <option value="5to" <?php echo $equipo['curso'] == '5to' ? 'selected' : ''; ?>>5to</option>
                        <option value="6to" <?php echo $equipo['curso'] == '6to' ? 'selected' : ''; ?>>6to</option>
                        <option value="7mo" <?php echo $equipo['curso'] == '7mo' ? 'selected' : ''; ?>>7mo</option>
                    </select>
                </div>
    
                <div class="flex-col">
                    <label>
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/bring-to-front.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                        División
                        <abbr title="Campo obligatorio">*</abbr>
                    </label>
                    <select id="division" name="division" required>
                        <?php if (in_array($equipo['curso'], ['1ro', '2do', '3ro'])): ?>
                            <option value="A" <?php echo $equipo['division'] == 'A' ? 'selected' : ''; ?>>A</option>
                            <option value="B" <?php echo $equipo['division'] == 'B' ? 'selected' : ''; ?>>B</option>
                            <option value="C" <?php echo $equipo['division'] == 'C' ? 'selected' : ''; ?>>C</option>
                        <?php else: ?>
                            <option value="1ra" <?php echo $equipo['division'] == '1ra' ? 'selected' : ''; ?>>1ra</option>
                            <option value="2da" <?php echo $equipo['division'] == '2da' ? 'selected' : ''; ?>>2da</option>
                        <?php endif; ?>
                    </select>
                </div>
            </div>

            <div class="flex-col">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/shield-question-mark.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Nombre del equipo
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="text" id="nombre_equipo" name="nombre_equipo" value="<?php echo htmlspecialchars($equipo['nombre_equipo']); ?>" required>
            </div>

            <div class="flex-col">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/iteration-cw.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Sistema de juego
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <select id="sistema_juego" name="sistema_juego" required onchange="actualizarSistema()">
                    <option value="6:0" <?php echo $equipo['sistema_juego'] == '6:0' ? 'selected' : ''; ?>>6:0</option>
                    <option value="4:2" <?php echo $equipo['sistema_juego'] == '4:2' ? 'selected' : ''; ?>>4:2</option>
                    <option value="5:1" <?php echo $equipo['sistema_juego'] == '5:1' ? 'selected' : ''; ?>>5:1</option>
                </select>
            </div>

            <?php if ($equipo['sistema_juego'] == '4:2'): ?>
            <div id="divTipoCuatroDos" class="flex-col">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/iteration-cw.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Tipo de 4:2
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <select id="tipo_cuatro_dos" name="tipo_cuatro_dos" required onchange="actualizarPosiciones()">
                    <option value="c" <?php echo $equipo['tipo_cuatro_dos'] == 'c' ? 'selected' : ''; ?>>4:2 con Centrales</option>
                    <option value="o" <?php echo $equipo['tipo_cuatro_dos'] == 'o' ? 'selected' : ''; ?>>4:2 con Opuestos</option>
                </select>
            </div>
            <?php endif; ?>

            <div class="flex-col">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/palette.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Color de remera
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="text" name="color_remera" id="color_remera" value="<?php echo htmlspecialchars($equipo['color_remera']); ?>" required>
            </div>

            <div class="flex-col">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/smartphone.svg'); ?>" alt="Telefono" class="label-icon" loading="lazy" decoding="async">
                    Teléfono del capitán
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="tel" name="telefono" id="telefono" value="<?php echo htmlspecialchars($equipo['telefono']); ?>" required>
            </div>

            <!-- Editable members table -->
            <div id="tablaIntegrantes">
                <div class="infoCompletTable">
                    <p>Ingresar apellido y nombre completos. No usar apodos ni abreviaciones.</p>
                </div>
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/users-round.svg'); ?>" alt="Numero" class="table-icon" loading="lazy" decoding="async">
                                </th>
                                <th>
                                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/a-large-small.svg'); ?>" alt="Nombre" class="table-icon" loading="lazy" decoding="async">
                                    Nombre
                                </th>
                                <th>Pos.</th>
                            </tr>
                        </thead>
                        <tbody id="bodyIntegrantes">
                            <?php 
                            $numero = 1;
                            foreach ($integrantes as $integrante): 
                            ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/info.svg'); ?>" alt="Obligatorio" class="icon" loading="lazy" decoding="async">
                                        <?php echo $numero; ?>
                                    </td>
                                    <td>
                                        <input type="text" name="integrante_<?php echo $numero; ?>" value="<?php echo htmlspecialchars($integrante['nombre']); ?>" required>
                                    </td>
                                    <td>
                                        <?php if ($equipo['sistema_juego'] == '6:0'): ?>
                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" alt="Sin posición" class="icon" loading="lazy" decoding="async">
                                        <?php elseif ($integrante['posicion']): ?>
                                            <select name="posicion_<?php echo $numero; ?>" required>
                                                <option value="Punta" <?php echo $integrante['posicion'] == 'Punta' ? 'selected' : ''; ?>>Punta</option>
                                                <option value="Opuesto" <?php echo $integrante['posicion'] == 'Opuesto' ? 'selected' : ''; ?>>Opuesto</option>
                                                <option value="Central" <?php echo $integrante['posicion'] == 'Central' ? 'selected' : ''; ?>>Central</option>
                                                <option value="Armador" <?php echo $integrante['posicion'] == 'Armador' ? 'selected' : ''; ?>>Armador</option>
                                                <option value="Libero" <?php echo $integrante['posicion'] == 'Libero' ? 'selected' : ''; ?>>Libero</option>
                                            </select>
                                        <?php else: ?>
                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/refresh-ccw.svg'); ?>" alt="Suplente" class="icon" loading="lazy" decoding="async">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php 
                            $numero++;
                            endforeach;
                            
                            for ($i = $numero; $i <= 8; $i++): 
                            ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td>
                                        <input type="text" name="integrante_<?php echo $i; ?>" placeholder="Nombre del integrante">
                                    </td>
                                    <td>
                                        <?php if ($equipo['sistema_juego'] != '6:0'): ?>
                                            <select name="posicion_<?php echo $i; ?>">
                                                <option value="">Suplente</option>
                                                <option value="Punta">Punta</option>
                                                <option value="Opuesto">Opuesto</option>
                                                <option value="Central">Central</option>
                                                <option value="Armador">Armador</option>
                                                <option value="Libero">Libero</option>
                                            </select>
                                        <?php else: ?>
                                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" alt="Sin posición" class="icon" loading="lazy" decoding="async">
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                </div>
                <div class="infoIcons flex-row">
                    <div class="infoIcon flex-row">
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/info.svg'); ?>" alt="" class="icon" loading="lazy" decoding="async">
                        <p>= Campo obligatorio</p>
                    </div>
                    <div class="infoIcon flex-row">
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" alt="" class="icon" loading="lazy" decoding="async">
                        <p>= Sin posición</p>
                    </div>
                    <div class="infoIcon flex-row">
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/refresh-ccw.svg'); ?>" alt="" class="icon" loading="lazy" decoding="async">
                        <p>= Suplente</p>
                    </div>
                </div>
            </div>
             
            <button type="submit" class="btn btn-primary btn-full-width">Guardar Cambios</button>

            <div class="bottom-actions">
                <a href="<?php echo buildPath($base_path, 'a/teams'); ?>" class="btn btn-secondary btn-full-width">← Volver a Equipos</a>
            </div>
        </form>
    </main>

    <script>
        function actualizarDivision() {
            const curso = document.getElementById('curso').value;
            const divisionSelect = document.getElementById('division');
            const isBasico = ['1ro', '2do', '3ro'].includes(curso);
            
            divisionSelect.innerHTML = isBasico ?
                '<option value="A">A</option><option value="B">B</option><option value="C">C</option>' :
                '<option value="1ra">1ra</option><option value="2da">2da</option>';
        }

        function actualizarSistema() {
            const sistema = document.getElementById('sistema_juego').value;
            const tipoDiv = document.getElementById('divTipoCuatroDos');
            
            if (sistema === '4:2') {
                if (!tipoDiv) {
                    const newDiv = document.createElement('div');
                    newDiv.id = 'divTipoCuatroDos';
                    newDiv.className = 'flex-col';
                    newDiv.innerHTML = `
                        <label>
                            <img src="<?php echo buildPath($base_path, 'assets/img/icons/iteration-cw.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                            Tipo de 4:2
                            <abbr title="Campo obligatorio">*</abbr>
                        </label>
                        <select id="tipo_cuatro_dos" name="tipo_cuatro_dos" required onchange="actualizarPosiciones()">
                            <option value="c">4:2 con Centrales</option>
                            <option value="o">4:2 con Opuestos</option>
                        </select>
                    `;
                    document.getElementById('sistema_juego').closest('.flex-col').after(newDiv);
                }
            } else {
                if (tipoDiv) {
                    tipoDiv.remove();
                }
            }
            
            actualizarPosiciones();
        }

        function actualizarPosiciones() {
            const sistema = document.getElementById('sistema_juego').value;
            const tbody = document.getElementById('bodyIntegrantes');
            const rows = tbody.querySelectorAll('tr');
            
            rows.forEach((row, index) => {
                const posCell = row.cells[2];
                const numero = index + 1;
                
                if (sistema === '6:0') {
                    posCell.innerHTML = '<img src="<?php echo buildPath($base_path, 'assets/img/icons/circle-slash.svg'); ?>" alt="Sin posición" class="icon" loading="lazy" decoding="async">';
                } else {
                    const hasValue = posCell.querySelector('select') && posCell.querySelector('select').value;
                    posCell.innerHTML = `
                        <select name="posicion_${numero}">
                            <option value="">Suplente</option>
                            <option value="Punta">Punta</option>
                            <option value="Opuesto">Opuesto</option>
                            <option value="Central">Central</option>
                            <option value="Armador">Armador</option>
                            <option value="Libero">Libero</option>
                        </select>
                    `;
                }
            });
        }
    </script>
</body>
</html>
