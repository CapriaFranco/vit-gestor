<?php
    function buildPath($base, $path) {
        if (empty($base)) {
            return '/' . ltrim($path, '/');
        }
        return $base . '/' . ltrim($path, '/');
    }
?>
