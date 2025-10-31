<?php
session_start();
include __DIR__ . '/../php/db.php';
require_once __DIR__ . '/../php/functions.php';
$base_path = defined('BASE_PATH') ? BASE_PATH : rtrim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Check admin authentication
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . buildPath($base_path, 'a/login'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['team_id'])) {
    $team_id = intval($_POST['team_id']);
    
    // Get the code associated with this team to mark it as unused
    $stmt_code = $db->prepare("SELECT codigo FROM codigos_acceso WHERE id_equipo = ?");
    $stmt_code->bind_param("i", $team_id);
    $stmt_code->execute();
    $result_code = $stmt_code->get_result();
    
    if ($result_code->num_rows > 0) {
        $code_row = $result_code->fetch_assoc();
        $codigo = $code_row['codigo'];
        
        // Mark code as unused
        $stmt_update = $db->prepare("UPDATE codigos_acceso SET usado = 0, id_equipo = NULL WHERE codigo = ?");
        $stmt_update->bind_param("s", $codigo);
        $stmt_update->execute();
    }
    
    // Delete team members first (foreign key constraint)
    $stmt_members = $db->prepare("DELETE FROM integrantes WHERE id_equipo = ?");
    $stmt_members->bind_param("i", $team_id);
    $stmt_members->execute();
    
    // Delete the team
    $stmt_team = $db->prepare("DELETE FROM equipos WHERE id = ?");
    $stmt_team->bind_param("i", $team_id);
    
    if ($stmt_team->execute()) {
        $_SESSION['delete_success'] = true;
        header('Location: ' . buildPath($base_path, '../a/teams'));
    } else {
        $_SESSION['delete_error'] = true;
        header('Location: ' . buildPath($base_path, '../a/teams/edit?id=' . $team_id));
    }
    exit;
}

// If not POST or no team_id, redirect to teams
header('Location: ' . buildPath($base_path, 'a/teams'));
exit;
?>
