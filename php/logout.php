<?php
    session_start();
    require_once __DIR__ . '/functions.php';
    
    $base_path = rtrim(str_replace('/php', '', dirname($_SERVER['SCRIPT_NAME'])), '/');
    
    // Destroy session
    session_unset();
    session_destroy();
    
    // Redirect to home
    header('Location: ' . $base_path . '/');
    exit;
?>
