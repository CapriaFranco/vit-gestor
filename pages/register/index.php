<?php
    session_start();
    include './php/db.php';

    require_once __DIR__ . '/../../php/functions.php';
    $base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

    function obtenerColores($db, $curso) {
        $tipo = in_array($curso,['1ro','2do','3ro']) ? 'basico' : 'superior';
        $sql = $tipo==='basico' ? 
            "SELECT DISTINCT color_remera FROM equipos WHERE curso IN ('1ro','2do','3ro')" :
            "SELECT DISTINCT color_remera FROM equipos WHERE curso IN ('4to','5to','6to','7mo')";
        $res = $db->query($sql);
        $colores=[];
        while($row=$res->fetch_assoc()) $colores[]=$row['color_remera'];
        return $colores;
    }

    if($_SERVER["REQUEST_METHOD"]=="POST"){
        // Validar que todos los campos requeridos estén presentes
        $campos_requeridos = ['curso', 'division', 'nombre_equipo', 'sistema_juego', 'color_remera', 'integrante_1'];
        foreach($campos_requeridos as $campo) {
            if(!isset($_POST[$campo]) || empty($_POST[$campo])) {
                die("Error: El campo $campo es requerido");
            }
        }
        
        $curso = $_POST['curso'];
        $division = $_POST['division'];
        $nombre_equipo = $_POST['nombre_equipo'];
        $sistema = $_POST['sistema_juego'];
        $color = $_POST['color_remera'];
        $capitan = $_POST['integrante_1'];

        $stmt=$db->prepare("INSERT INTO equipos (curso,division,nombre_equipo,sistema_juego,color_remera,capitan) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("ssssss",$curso,$division,$nombre_equipo,$sistema,$color,$capitan);
        $stmt->execute();
        $id_equipo=$stmt->insert_id;

        $max=$sistema==='5:1'?7:6;
        for($i=1;$i<=8;$i++){
            if(!empty($_POST["integrante_$i"])){
                $nombre=$_POST["integrante_$i"];
                $pos=$_POST["posicion_$i"] ?? NULL;
                // Limpiamos el número de la posición antes de guardar
                if ($pos) {
                    $pos = preg_replace('/\s+\d+$/', '', $pos);
                }
                $stmt2=$db->prepare("INSERT INTO integrantes (id_equipo,nombre,posicion) VALUES (?,?,?)");
                $stmt2->bind_param("iss",$id_equipo,$nombre,$pos);
                $stmt2->execute();
            }
        }
        $_SESSION['registro_exitoso'] = true;
        header('Location: ' . buildPath($base_path, 'registered'));
        exit;
    }

    // Si es una recarga de página POST, redirigir a index
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SERVER['HTTP_CACHE_CONTROL']) && $_SERVER['HTTP_CACHE_CONTROL'] === 'max-age=0') {
        header('Location: ' . buildPath($base_path, ''));
        exit;
    }
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Torneo de Voley - Edición 2025</title>
     <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">
</head>
<body>
    <main>
        <form method="POST" id="formEquipo" class="form">
            <div class="form-header">
                <img src="<?php echo buildPath($base_path, 'assets/img/vit-logo-transparent.png'); ?>" alt="VIT Logo" loading="lazy" decoding="async">
                <h1>Torneo de Voley 2025</h1>
                <p>Registro de Equipos</p>
            </div>

            <div class="curso">
                <div id="div1" class="flex-col">
                    <label>
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/squares-unite.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                        Curso
                        <abbr title="Campo obligatorio">*</abbr>
                    </label>
                    <select id="curso" name="curso" required onchange="mostrarDivision()">
                        <option value="" selected disabled>Seleccionar</option>
                        <option value="1ro">1ro</option>
                        <option value="2do">2do</option>
                        <option value="3ro">3ro</option>
                        <option value="4to">4to</option>
                        <option value="5to">5to</option>
                        <option value="6to">6to</option>
                        <option value="7mo">7mo</option>
                    </select>
                </div>
    
                <div id="div2" class="flex-col dn">
                    <label>
                        <img src="<?php echo buildPath($base_path, 'assets/img/icons/bring-to-front.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                        División
                        <abbr title="Campo obligatorio">*</abbr>
                    </label>
                    <select id="division" name="division" required onchange="mostrarNombreEquipo()">
                        <option value="" selected disabled>Seleccionar</option>
                    </select>
                </div>
            </div>

            <div id="div3" class="flex-col dn">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/shield-question-mark.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Nombre del equipo
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="text" id="nombre_equipo" name="nombre_equipo" placeholder="Ingrese el nombre del equipo" title="Ingrese el nombre del equipo" required>
            </div>

            <div id="div4" class="flex-col dn">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/iteration-cw.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Sistema de juego
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <select id="sistema_juego" name="sistema_juego" required onchange="mostrarIntegrantes()">
                    <option value="" selected disabled>Seleccionar</option>
                    <option value="6:0">6:0</option>
                    <option value="4:2">4:2</option>
                    <option value="5:1">5:1</option>
                </select>
            </div>

            <div id="tablaIntegrantes" class="dn">
                <div class="table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th>
                                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/users-round.svg'); ?>" alt="" class="table-icon" loading="lazy" decoding="async">
                                </th>
                                <th>
                                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/a-large-small.svg'); ?>" alt="" class="table-icon" loading="lazy" decoding="async">
                                    Nombre
                                </th>
                                <th>Pos.</th>
                            </tr>
                        </thead>
                        <tbody id="bodyIntegrantes"></tbody>
                    </table>
                </div>
            </div>

            <div id="colorDiv" class="flex-col dn">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/palette.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Color de remera
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="text" name="color_remera" id="color_remera" placeholder="Ej: Rojo, Azul, Verde" title="Ingrese el color de la remera del equipo" required>
                <div id="coloresUsados"></div>
            </div>

            <button type="submit" id="btnSubmit" class="dn">Registrar equipo</button>
        </form>
    </main>
    <script src="<?php echo buildPath($base_path, 'scripts/main.js'); ?>"></script>
</body>
</html>
