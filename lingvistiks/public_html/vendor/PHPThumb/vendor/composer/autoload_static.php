<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd92a2ea883d8fc179c244965b3c4d776
{
    public static $prefixesPsr0 = array (
        'P' => 
        array (
            'PHPThumb\\Tests' => 
            array (
                0 => __DIR__ . '/../..' . '/tests',
            ),
            'PHPThumb' => 
            array (
                0 => __DIR__ . '/../..' . '/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixesPsr0 = ComposerStaticInitd92a2ea883d8fc179c244965b3c4d776::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}
