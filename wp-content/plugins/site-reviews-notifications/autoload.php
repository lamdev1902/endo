<?php

defined('WPINC') || die;

spl_autoload_register(function ($className) {
    $namespaces = [
        'GeminiLabs\\Pelago\\Emogrifier\\' => __DIR__.'/vendors/pelago/emogrifier/',
        'GeminiLabs\\SiteReviews\\Addon\\Notifications\\' => __DIR__.'/plugin/',
        'GeminiLabs\\Symfony\\Component\\CssSelector\\' => __DIR__.'/vendors/symfony/css-selector/',
    ];
    foreach ($namespaces as $prefix => $baseDir) {
        $len = strlen($prefix);
        if (0 !== strncmp($prefix, $className, $len)) {
            continue;
        }
        $file = $baseDir.str_replace('\\', '/', substr($className, $len)).'.php';
        if (file_exists($file)) {
            require $file;
            break;
        }
    }
});
