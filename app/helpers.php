<?php

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('adjustBrightness')) {
    function adjustBrightness($hex, $percent) {
        $hex = ltrim($hex, '#');
        
        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = min(255, intval($r * (100 + $percent) / 100));
        $g = min(255, intval($g * (100 + $percent) / 100));
        $b = min(255, intval($b * (100 + $percent) / 100));

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
} 