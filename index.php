<?php
    // Detectar si estamos en InfinityFree o en local
    $script_name = $_SERVER['SCRIPT_NAME'];
    $is_infinityfree = (strpos($script_name, '/vit-gestor/') === false);
    
    if ($is_infinityfree) {
        // En InfinityFree (archivos en raíz)
        $base_path = '';
    } else {
        // En local (con carpeta vit-gestor)
        $base_path = rtrim(dirname($script_name), '/');
    }
    
    define('BASE_PATH', $base_path);
    
    $url = $_GET['url'] ?? '';
    
    if ($url == '') { 
        include __DIR__ . '/pages/register/index.php'; 
    } 
    elseif ($url == 'registered' || $url == 'registered/') { 
        include __DIR__ . '/pages/registered/index.php'; 
    }
    else { 
        include __DIR__ . '/pages/err/404.php'; 
    }
?>
