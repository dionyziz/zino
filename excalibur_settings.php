<?php
    return array(
    	'readonly'       	=> false,
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
        'imagesupload'    	=> array(
            'ip'   => '87.230.27.77',
            'host' => 'images.chit-chat.gr',
            'port' => 80,
            'url'  => '/upload3.php'
        ),
        'chat'              => array(
            'enabled' => true,
            'applet' => 'http://static.chit-chat.gr/chat/ice_queen/alpha/Frontend.class'
        )
    );
?>
