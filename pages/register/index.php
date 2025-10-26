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
    <link rel="stylesheet" href="<?php echo buildPath($base_path, 'styles/main.css?v=2'); ?>">
    <link rel="icon" type="image/x-icon" href="<?php echo buildPath($base_path, 'assets/img/favicon.ico'); ?>">

    <!-- Updated meta tags with absolute URLs and proper metadata for WhatsApp compatibility -->
    <meta name="description" content="Registro oficial para el Torneo de Voley Interno 2025 del VIT. Inscribe tu equipo y participa en la competencia de voleibol más emocionante del año.">
    <meta name="keywords" content="torneo voley, voleibol, VIT, registro equipos, torneo 2025, competencia deportiva, voleibol escolar">
    <meta name="author" content="VIT - Torneo de Voley">
    <meta name="robots" content="index, follow">
    <meta name="theme-color" content="#94d0af">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Registro - Torneo de Voley VIT 2025">
    <meta property="og:description" content="Inscribe tu equipo en el Torneo de Voley Interno 2025. Completa el formulario de registro y únete a la competencia.">
    <meta property="og:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta property="og:image:secure_url" content="<?php echo 'https://' . $_SERVER['HTTP_HOST'] . buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="628">
    <meta property="og:image:alt" content="Logo del Torneo de Voley VIT 2025">
    <meta property="og:url" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
    <meta property="og:site_name" content="Torneo de Voley VIT">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Registro - Torneo de Voley VIT 2025">
    <meta name="twitter:description" content="Inscribe tu equipo en el Torneo de Voley Interno 2025. Completa el formulario de registro y únete a la competencia.">
    <meta name="twitter:image" content="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . buildPath($base_path, 'assets/img/vit-logo-redes.png'); ?>">
    <meta name="twitter:image:alt" content="Logo del Torneo de Voley VIT 2025">
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
                <input type="text" id="nombre_equipo" name="nombre_equipo" placeholder="Ingrese el nombre del equipo" pattern="[A-Za-z0-9áéíóúÁÉÍÓÚñÑ _-]+" title="Ingrese el nombre del equipo. Solo se permiten letras, números, espacios, guiones o guion bajo" maxlength="100" minlength="3" required>
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
                <div class="infoCompletTable">
                    <p>Ingresar apellido y nombre completos. No usar apodos ni abreviaciones.</p>
                </div>
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

            <div id="colorDiv" class="flex-col dn">
                <label>
                    <img src="<?php echo buildPath($base_path, 'assets/img/icons/palette.svg'); ?>" alt="" class="label-icon" loading="lazy" decoding="async">
                    Color de remera
                    <abbr title="Campo obligatorio">*</abbr>
                </label>
                <input type="text" name="color_remera" id="color_remera" placeholder="Ej: Rojo, Azul, Verde" pattern="[A-Za-záéíóúÁÉÍÓÚñÑ ]+" title="Ingrese el color de la remera del equipo. Solo se permiten letras A-Z o a-z" maxlength="100" minlength="4" required>
                <div class="infoRepetirColor"><p>No repetir colores de los equipos ya registrados.</p></div>
                <div id="coloresUsados"></div>
            </div>
             
            <button type="submit" id="btnSubmit" class="dn">Registrar equipo</button>

            <div id="equiposRegistrados" class="teams-link-container">
                <p>Ver equipos ya registrados <a href="teams/" class="btn btn-outline">aquí</a></p>
            </div>

            <div class="gh-repo-container">
                <img src="<?php echo buildPath($base_path, 'assets/img/icons/github.svg'); ?>" alt="Github repositorio icon" class="icon" loading="lazy" decoding="async">
                <p>Ver repositorio <a href="https://github.com/CapriaFranco/vit-gestor" target="_blank" class="btn-repo">aquí</a></p>
            </div>
        </form>
    </main>
    <script src="<?php echo buildPath($base_path, 'scripts/main.js'); ?>"></script>
</body>
</html>
