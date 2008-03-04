<?php
    return array(
    	'readonly'       	=> 0,
        'allowuploads'	 	=> 10,
    	'membername'     	=> 'Zino',
    	'nocomments'     	=> false,
    	'nofilters'      	=> true,
        'usersubdomains'    => 'http://*.beta.chit-chat.gr/reloaded/',
    	'ushoutbox'      	=> 'shoutbox',
    	'cookiename'     	=> 'cc_login_7',
        'cookiedomain'    	=> '.beta.chit-chat.gr',
        'staticimagesurl' 	=> 'http://static.zino.gr/images/',
        'imagesurl'       	=> 'http://images.zino.gr/',
		'anonymouscomments' => false,
        'allowregisters'    => true,
        'mysql2phpdate'     => '- INTERVAL 2 HOUR',
        'pminboxname'       => 'Εισερχόμενα', // TODO: change db structure
        'pmoutboxname'      => 'Εξερχόμενα', // TODO: change db structure
        'memcache'          => array(
            'type'      => 'memcached',
            'hostname'  => '127.0.0.1',
            'port'      => '11211'
        ),
        'imagesupload'    	=> array(
            'ip'   => '87.230.27.77',
            'host' => 'images.zino.gr',
            'port' => 80,
            'url'  => '/upload3.php'
        ),
        'chat'              => array(
            'enabled' => 50,
            'applet' => 'http://static.zino.gr/chat/ice_queen/alpha/frame'
        )
    );
?>
