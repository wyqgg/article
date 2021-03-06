<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2440a74dbe616d6f7645648188c864aa
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPExcel' => 
            array (
                0 => __DIR__ . '/..' . '/phpoffice/phpexcel/Classes',
            ),
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2440a74dbe616d6f7645648188c864aa::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2440a74dbe616d6f7645648188c864aa::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInit2440a74dbe616d6f7645648188c864aa::$prefixesPsr0;
            $loader->classMap = ComposerStaticInit2440a74dbe616d6f7645648188c864aa::$classMap;

        }, null, ClassLoader::class);
    }
}
