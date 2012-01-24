<?php
    $settings = array(
        'applicationname' => 'Zino',
        'rootdir'         => '/var/www/zino.gr/html',
        'resourcesdir'    => '/var/www/zino.gr/beta/resources',
        'imagesurl'       => 'http://static.zino.gr/phoenix/',
        'production'      => true,
        'hostname'        => 'zino.gr',
        'hostnameforce'   => false, // don't force a hostname
        'url'             => '',
        'port'            => 80,
        'webaddress'      => 'http://www.zino.gr',
        'legalreferers'   => '#^https?://((?<!-)[a-z0-9_-]+(?!-)\.)*zino\.gr#i', 
        'timezone'        => 'UTC',
        'language'        => 'el',
        'locale'          => 'el_EL',
        'dbschemaversion'      => 68,
        'elementschemaversion' => 224,
        'databases'       => array( // prefix all keys with "db"
            'db' => array(
                'driver'   => 'mysql',
                'hostname' => '88.198.246.218',
                'name'     => 'zinolive',
                'username' => 'zinolive',
                'password' => 'password',
                'charset'  => '\'utf8\'',
                'prefix'   => '',
                'tables'   => array(
                    'adminactions',
                    'ads',
                    'adplaces',
                    'albums',
                    'answers',
                    'badges' ,
                    'bannedips',
                    'bannedusers',
                    'block',
                    'bulk',
					'chatchannels',
					'chatparticipants',
                    'chatvideo',
                    'coins',
                    'comments',
                    'contacts',
                    'favourites',
                    'happenings',
                    'happeningparticipants',
                    'images',
                    'imagesfrontpage',
                    'imagetags',
                    'institutions',
                    'journals',
					'journalsfrontpage',
					'journalstickies',
                    'lastactive',
                    'loginattempts',
                    'moods',
                    'notify',
                    'olduserprofiles',
                    'pageviews',
                    'passwordrequests',
                    'places',
                    'pmfolders',
                    'pmmessageinfolder',
                    'pmmessages',
                    'polloptions',
                    'polls',
					'pollsfrontpage',
                    'questions',
                    'report',
                    'relations',
                    'relationtypes',
                    'schools',
                    'sequences',
                    'song',
                    'shoutbox',
					'storetypes',
					'storeitems',
					'storeproperties',
					'storepurchases',
					'storepurchaseproperties',
                    'storeactionshots',
                    'tags',
                    'universities',
                    'usercounts',
                    'userprofiles',
                    'users',
                    'usersettings',
                    'votes',
                    'statusbox',
                    'applications',
					'memoryusage'
                )
            )
        ),
        'memcache' => array(
            // 'type'      => 'memcached',
            'type'      => 'dummy',
            'hostname'  => '88.198.246.218',
            'port'      => '11211'
        ),
        '_excalibur' => array(
            'jsversion'          => 161,
            'cssversion'         => 124469,
            'readonly'           => false,
            'membername'         => 'Zino',
            'ushoutbox'          => 'shoutbox',
            'cookiename'         => 'zino_login_8',
            'cookiedomain'       => '.zino.gr',
            'usersubdomains'     => 'http://*.zino.gr/',
            'iphoneurl'          => 'iphone.php',
            'staticimagesurl'    => 'http://static.zino.gr/phoenix/',
            'staticjsurl'        => 'http://static.zino.gr/js/',
            'staticcssurl'       => 'http://static.zino.gr/css/',
            'imagesurl'          => 'http://images2.zino.gr/media/',
            'php2mysqldate'      => '0',
            'mysql2phpdate'      => '',
            'imagesupload'       => array(
                'ip'   => '88.198.246.219',
                'host' => 'images2.zino.gr',
                'port' => 80,
                'url'  => '/upload4.php'
            ),
            'spotdaemon'        => array(
                'enabled'       => false,
                'address'       => 'iris.kamibu.com',
                'port'          => '21490'
            )
        )
    );  
    if ( $_SERVER[ 'DOCUMENT_ROOT' ] == '/var/www/zino.gr/beta/phoenix' ) {
        // load beta settings from settings-beta.php
        function SettingsMerge( $settings, $settingsbeta ) {
            foreach ( $settingsbeta as $key => $value ) {
                if ( is_array( $value ) && isset( $settings[ $key ] ) ) {
                    $settings[ $key ] = SettingsMerge( $settings[ $key ], $settingsbeta[ $key ] );
                    continue;
                }
                $settings[ $key ] = $value;
            }
            return $settings;
        }
        $betasettings = require_once 'settings-beta.php';
        $settings = SettingsMerge( $settings, $betasettings );
    }

    $settings[ '_excalibur' ][ 'mysql2phpdate' ] = ' - INTERVAL ' . $settings[ '_excalibur' ][ 'php2mysqldate' ] . ' SECOND';

    return $settings;
?>
