<?php
    include 'db.php';

    header('Content-Type: application/json');

    if (!isset($_GET['curso']) || empty($_GET['curso'])) {
        echo json_encode([]);
        exit;
    }

    $curso = $_GET['curso'];

    // Determine if it's basico or superior
    $tipo = in_array($curso, ['1ro', '2do', '3ro']) ? 'basico' : 'superior';

    // Query to get distinct colors based on type
    if ($tipo === 'basico') {
        $sql = "SELECT DISTINCT color_remera FROM equipos WHERE curso IN ('1ro','2do','3ro')";
    } else {
        $sql = "SELECT DISTINCT color_remera FROM equipos WHERE curso IN ('4to','5to','6to','7mo')";
    }

    $result = $db->query($sql);

    $colores = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            if (!empty($row['color_remera'])) {
                $colores[] = $row['color_remera'];
            }
        }
    }

    echo json_encode($colores);
?>
