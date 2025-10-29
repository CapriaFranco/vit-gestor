<?php
ob_start();

session_start();

include __DIR__ . '/db.php';

ob_clean();

header('Content-Type: application/json; charset=utf-8');

// Check if logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'No autorizado']);
    exit;
}

// Get POST data
$team_id = $_POST['team_id'] ?? null;
$nombre_equipo = $_POST['nombre_equipo'] ?? null;
$color_remera = $_POST['color_remera'] ?? null;
$curso = $_POST['curso'] ?? null;
$division = $_POST['division'] ?? null;
$sistema_juego = $_POST['sistema_juego'] ?? null;
$tipo_cuatro_dos = $_POST['tipo_cuatro_dos'] ?? null;
$integrantes = json_decode($_POST['integrantes'] ?? '[]', true);

if (!$team_id || !$nombre_equipo || !$color_remera || !$curso || !$division || !$sistema_juego) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
    exit;
}

try {
    if ($db->connect_error) {
        throw new Exception("Error de conexión: " . $db->connect_error);
    }
    
    $db->begin_transaction();
    
    // Update team basic info
    $stmt = $db->prepare("
        UPDATE equipos 
        SET nombre_equipo = ?,
            color_remera = ?,
            curso = ?,
            division = ?,
            sistema_juego = ?,
            tipo_cuatro_dos = ?
        WHERE id = ?
    ");
    
    $stmt->bind_param(
        "ssssssi",
        $nombre_equipo,
        $color_remera,
        $curso,
        $division,
        $sistema_juego,
        $tipo_cuatro_dos,
        $team_id
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Error al actualizar equipo: " . $stmt->error);
    }
    $stmt->close();
    
    // Delete all existing integrantes first
    $stmt = $db->prepare("DELETE FROM integrantes WHERE id_equipo = ?");
    $stmt->bind_param("i", $team_id);
    
    if (!$stmt->execute()) {
        throw new Exception("Error al eliminar integrantes: " . $stmt->error);
    }
    $stmt->close();
    
    // Insert new integrantes
    foreach ($integrantes as $integrante) {
        $nombre = $integrante['nombre'];
        $posicion = $integrante['posicion'] ?? null;
        
        $stmt = $db->prepare("
            INSERT INTO integrantes (id_equipo, nombre, posicion)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $team_id, $nombre, $posicion);
        
        if (!$stmt->execute()) {
            throw new Exception("Error al insertar integrante: " . $stmt->error);
        }
        $stmt->close();
    }
    
    $db->commit();
    
    echo json_encode(['success' => true, 'message' => 'Equipo actualizado correctamente']);
    
} catch(Exception $e) {
    if (isset($db)) {
        $db->rollback();
    }
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error al actualizar: ' . $e->getMessage()]);
}

if (isset($db)) {
    $db->close();
}

ob_end_flush();
?>
