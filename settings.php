<?php
    return array(
        'applicationname' => 'Chit-Chat',
        'rootdir'         => '/var/www/vhosts/excalibur.qlabs.gr/httpdocs/sandbox',
        'resourcesdir'    => '/var/www/vhosts/excalibur.qlabs.gr/httpdocs/resources',
        'imagesurl'       => 'http://static.chit-chat.gr/images/',
        'production'      => false,
        'hostname'        => 'excalibur.qlabs.gr',
        'url'             => 'sandbox',
        'port'            => 80,
        'webaddress'      => 'excalibur.qlabs.gr/sandbox',
        'timezone'        => 'UTC',
        'language'        => 'el',
        'databases'       => array( // prefix all keys with "db"
            'db' => array(
                'name'     => 'chitchat', // sandbox
                'hostname' => 'localhost',
                'username' => 'chitchat',
                'password' => '7paz?&aS',
                /*
                'name'     => 'excalibur-sandbox', // Notice: 'excalibur-sandbox' is actually live!
                'hostname' => 'localhost',
                'username' => 'excalibursandbox',
                'password' => 'viuhluqouhoa',
                */
                'charset'  => 'DEFAULT',
                'prefix'   => 'merlin_',
                'tables'   => array(
                	'articles'      	=> 'articles',
                	'bans'          	=> 'ipban',
                	'bulk'          	=> 'bulk',
                	'categories'    	=> 'categories',
                	'chats'         	=> 'chat',
                	'comments'      	=> 'comments',
                	'faqquestions'  	=> 'faqquestions',
                	'faqcategories'		=> 'faqcategories',
                    'friendrel'		    => 'friendrel',
                	'images'        	=> 'images',
                	'logs'          	=> 'logs',
                	'memcachesql'   	=> 'memcache',
					'pmfolders'			=> 'pmfolders',
					'pmmessageinfolder' => 'pmmessageinfolder',
					'pmmessages'		=> 'pmmessages',
                	'pageviews'     	=> 'pageviews',
                	'places'        	=> 'places',
					'profileanswers' 	=> 'profilea',
                	'polls'         	=> 'polls',
                	'polloptions'   	=> 'polloptions',
                	'questions'     	=> 'profileq',
                	'relations'     	=> 'relations',
                	'revisions'     	=> 'revisions',
                	'ricons'        	=> 'ricons',
                	'searches'      	=> 'searches',
                	'shoutbox'      	=> 'shoutbox',
                	'starring'     		=> 'starring',
                	'templates'     	=> 'templates',
                	'userbans'      	=> 'userban',
                	'users'         	=> 'users',
                	'usershout'     	=> 'usershout',
                	'userspaces'    	=> 'articles',
                	'usrevisions'   	=> 'revisions',
                	'exvars'        	=> 'vars',
                	'votes'         	=> 'votes',
                	'albums'        	=> 'albums',
                	'notify'        	=> 'notify'
                )
            )
        )
    );
?>
