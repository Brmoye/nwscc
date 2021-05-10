<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit6fad77b5d50c51ec4875be49483990ea
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'Picqer\\Barcode\\' => 15,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Picqer\\Barcode\\' => 
        array (
            0 => __DIR__ . '/..' . '/picqer/php-barcode-generator/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit6fad77b5d50c51ec4875be49483990ea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit6fad77b5d50c51ec4875be49483990ea::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit6fad77b5d50c51ec4875be49483990ea::$classMap;

        }, null, ClassLoader::class);
    }
}
