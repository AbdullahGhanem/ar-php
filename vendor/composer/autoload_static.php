<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit618b0a46a5b4e8435ba86109e8420ae5
{
    public static $prefixLengthsPsr4 = array (
        'J' => 
        array (
            'Johntaa\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Johntaa\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/arabic',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit618b0a46a5b4e8435ba86109e8420ae5::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit618b0a46a5b4e8435ba86109e8420ae5::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit618b0a46a5b4e8435ba86109e8420ae5::$classMap;

        }, null, ClassLoader::class);
    }
}
