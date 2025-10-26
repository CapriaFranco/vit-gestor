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
    elseif ($url == 'teams' || $url == 'teams/') { 
        include __DIR__ . '/pages/registered-teams/index.php'; 
    }
    elseif ($url == 'admin' || $url == 'admin/') { 
        include __DIR__ . '/pages/admins/index.php'; 
    }
    elseif ($url == 'dash' || $url == 'dash/') { 
        include __DIR__ . '/pages/admins/dashboard/index.php'; 
    }
    elseif ($url == 'err/403' || $url == 'err/403/') { 
        include __DIR__ . '/pages/err/403.php'; 
    }
    elseif ($url == 'err/404' || $url == 'err/404/') { 
        include __DIR__ . '/pages/err/404.php'; 
    }
    elseif ($url == 'err/500' || $url == 'err/500/') { 
        include __DIR__ . '/pages/err/500.php'; 
    }
    else { 
        include __DIR__ . '/pages/err/404.php'; 
    }
?>
