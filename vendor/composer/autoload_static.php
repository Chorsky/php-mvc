<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit01fb3969846dd2b0379791986a8e9aa3
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit01fb3969846dd2b0379791986a8e9aa3::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit01fb3969846dd2b0379791986a8e9aa3::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit01fb3969846dd2b0379791986a8e9aa3::$classMap;

        }, null, ClassLoader::class);
    }
}
