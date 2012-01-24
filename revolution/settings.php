<?php
    $settings = array(
        'db' => array(
            /*
            'host' => 'code.kamibu.com',
            'user' => 'zinophoenix',
            'password' => 'password',
            'name' => 'zinophoenix'
            */
            'host' => 'localhost',
            'user' => 'zinolive',
            'password' => 'password',
            'name' => 'zinolive'
        ),
        'base' => 'http://zino.gr',
        'enablemc' => true,
        'cookiename' => 'zino_login_8',
        'cookiedomain' => '.zino.gr',
        'beta' => false,
        'xslversion' => 19,
        'imagesurl' => 'http://static.zino.gr/phoenix/',
        'imagesuploadurl' => 'images2.zino.gr/upload4.php',
        'spotdaemon'        => array(
            'enabled'       => true,
            'address'       => 'iris.kamibu.com',
            'port'          => '21490'
        ),
        'presence' => array(
            'url'           => 'http://presence.zino.gr:8124/users/list'
        ),
        'cachecontrol' => array(
            // for JS/CSS uncaching see the file xslt/html.xsl
            'xslversion' => '3'
        )
    );
    
    if ( file_exists( 'localtest.php' ) ) {
        // load local settings from localtest.php
        function SettingsMerge( $settings, $settingslocal ) {
            foreach ( $settingslocal as $key => $value ) {
                if ( is_array( $value ) && isset( $settings[ $key ] ) ) {
                    $settings[ $key ] = SettingsMerge( $settings[ $key ], $settingslocal[ $key ] );
                    continue;
                }
                $settings[ $key ] = $value;
            }
            return $settings;
        }
        $localsettings = include 'localtest.php';
        $settings = SettingsMerge( $settings, $localsettings );
    }
    
    return $settings;
?>
