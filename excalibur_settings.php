<?php
    return array(
    	'readonly'       	=> 0,
        'allowuploads'	 	=> 10,
    	'membername'     	=> 'Chit-Chatter',
    	'nocomments'     	=> false,
    	'nofilters'      	=> true,
    	'ushoutbox'      	=> 'shoutbox',
    	'cookiename'     	=> 'cc_login_7',
        'cookiedomain'    	=> '.beta.chit-chat.gr',
        'staticimagesurl' 	=> 'http://static.chit-chat.gr/images/',
        'imagesurl'       	=> 'http://images.chit-chat.gr/',
		'anonymouscomments' => false,
        'allowregisters'    => true,
        'mysql2phpdate'     => '- INTERVAL 2 HOUR',
        'memcache'          => array(
            'type'      => 'memcached',
            'hostname'  => '127.0.0.1',
            'port'      => '11211'
        ),
        'imagesupload'    	=> array(
            'ip'   => '87.230.27.77',
            'host' => 'images.chit-chat.gr',
            'port' => 80,
            'url'  => '/upload3.php'
        ),
        'chat'              => array(
            'enabled' => 50,
            'applet' => 'http://static.chit-chat.gr/chat/ice_queen/alpha/frame'
        )
    );
?>
