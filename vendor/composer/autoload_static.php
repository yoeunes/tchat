<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitffabfe2423a637c15632c070f6e9d49b
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

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitffabfe2423a637c15632c070f6e9d49b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitffabfe2423a637c15632c070f6e9d49b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
