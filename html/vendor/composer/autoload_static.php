<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita14164322ab63c383e020ee4e2f21969
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'SpotifyWebAPI\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'SpotifyWebAPI\\' => 
        array (
            0 => __DIR__ . '/..' . '/jwilsson/spotify-web-api-php/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita14164322ab63c383e020ee4e2f21969::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita14164322ab63c383e020ee4e2f21969::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
