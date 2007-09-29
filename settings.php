<?php
    return array(
        'applicationname' => 'Chit-Chat',
        'rootdir'         => '/srv/www/vhosts/chit-chat.gr/subdomains/beta/httpsdocs',
        'resourcesdir'    => '/srv/www/vhosts/chit-chat.gr/subdomains/beta/httpsdocs/resources',
        'imagesurl'       => 'http://static.chit-chat.gr/images/',
        'production'      => false,
        'hostname'        => 'beta.chit-chat.gr',
        'url'             => '',
        'port'            => 80,
        'webaddress'      => 'https://beta.chit-chat.gr',
        'timezone'        => 'UTC',
        'language'        => 'el',
        'databases'       => array( // prefix all keys with "db"
            'db' => array(
                'name'     => 'ccbeta', // sandbox
                'hostname' => 'localhost',
                'username' => 'ccbeta',
                'password' => 'IkJ84nZT',
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
                    'dictionaries'      => 'dictionaries',
                    'dictionarywords'   => 'dictionarywords',
                	'faqquestions'  	=> 'faqquestions',
                	'faqcategories'		=> 'faqcategories',
                    'friendrel'		    => 'friendrel',
                	'images'        	=> 'images',
                    'interesttags'      => 'interesttags',
					'latestimages'		=> 'latestimages',
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
