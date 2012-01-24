<?php
    // This file is only included when in a non-production environment.
    // This allows us for settings branching accordingly:
    // * The settings.php file contains common and production settings.
    // * The settings-beta.php file overwrites those for the sandbox.

    $ret = array(
        'rootdir'         => '/var/www/zino.gr/beta/phoenix',
        // 'imagesurl'       => 'http://static.beta.zino.gr/phoenix/',
        'production'      => false,
        'hostname'        => 'beta.zino.gr',
        'url'             => '/',
        'webaddress'      => 'http://beta.zino.gr',
        // 'legalreferers'   => '#^https?://((?<!-)[a-z0-9_-]+(?!-)\.)*beta\.zino\.gr#i',
        'databases'       => array(
            'db' => array(
                'name'     => 'zinophoenix', // sandbox
                'username' => 'zinophoenix',
                'password' => 'password',
                'hostname' => 'localhost'
            )
        ),
        'memcache' => array(
            'type'      => 'memcached',
            'hostname'  => '127.0.0.1',
            'port'      => '11211'
        ),
        '_excalibur' => array(
            'cookiedomain'      => '.beta.zino.gr',
            'usersubdomains'    => 'http://*.beta.zino.gr/',
            'iphoneurl'         => 'iphone.php',
            // 'staticimagesurl'    => 'http://static.beta.zino.gr/phoenix/',
            'staticjsurl'        => 'http://static.beta.zino.gr/js/',
            'staticcssurl'       => 'http://static.beta.zino.gr/css/',
            'php2mysqldate'     => 3600,
            'spotdaemon'        => array(
                'enabled'       => true,
                'address'       => 'europa.kamibu.com',
                'port'          => '21490'
            )
        )
    );
    
    $ret[ '_excalibur' ][ 'mysql2phpdate' ] = ' - INTERVAL ' . $ret[ '_excalibur' ][ 'php2mysqldate' ] . ' SECOND';
    
    return $ret;
?>
