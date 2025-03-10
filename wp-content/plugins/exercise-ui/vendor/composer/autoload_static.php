<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit1e6e7f6d6f5baa4a9e23947b92368010
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit1e6e7f6d6f5baa4a9e23947b92368010::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit1e6e7f6d6f5baa4a9e23947b92368010::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit1e6e7f6d6f5baa4a9e23947b92368010::$classMap;

        }, null, ClassLoader::class);
    }
}
