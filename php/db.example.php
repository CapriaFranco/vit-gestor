<?php
// Archivo de ejemplo para configuración de base de datos
// Copia este archivo como db.php y actualiza con tus datos reales

$script_name = $_SERVER['SCRIPT_NAME'];
$is_infinityfree = (strpos($script_name, '/vit-gestor/') === false);

if ($is_infinityfree) {
    // Configuración para InfinityFree - ACTUALIZA ESTOS VALORES
    $servername = "sql309.infinityfree.com";
    $username = "tu_usuario_infinityfree";
    $password = "tu_password_infinityfree";
    $dbname = "tu_base_datos_infinityfree";
} else {
    // Configuración para local (XAMPP)
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "vit_gestor_db";
}

$db = new mysqli($servername, $username, $password, $dbname);

if ($db->connect_error) {
    die("Error de conexión a la base de datos. Por favor, contacta al administrador.");
}
?>
