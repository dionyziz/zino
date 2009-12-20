<?php

    class ElementDeveloperAbresasOnline extends Element {
        public function Render() {
            global $libs;
            global $xc_settings;

            $libs->Load( 'user/user' );

            $finder = New UserFinder();
            $onlineUsers = $finder->FindOnlineWithDetails();

            foreach ( $onlineUsers as $onlineUser ) {
                ?><div id="float: left;"><?php
                Element( 'user/avatar', $onlineUser[ 'image_id' ], $onlineUser[ 'user_id' ], $onlineUser[ 'image_width' ], $onlineUser[ 'image_height' ], $onlineUser[ 'user_name' ], IMAGE_CROPPED_100x100 );
                ?></div><div><a href="<?php
                Element( 'user/url', $onlineUser[ 'user_id' ], $onlineUser[ 'user_subdomain' ] );
                ?>"><?php
                echo $onlineUser[ 'user_name' ];
                ?></a> <img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>moods/<?php
                echo $onlineUser[ 'mood_url' ];
                ?>" /></div><?php
            }
        }
    }

?>
