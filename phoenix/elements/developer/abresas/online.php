<?php

    class ElementDeveloperAbresasOnline extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'user/user' );

            $finder = New UserFinder();
            $onlineUsers = $finder->FindOnlineWithDetails();

            foreach ( $onlineUsers as $onlineUser ) {
                ?><div id="float: left;"><?php
                // Element( 'user/avatar', $onlineUser[ 'image_id' ], $onlineUser[ 'user_id' ], $onlineUser[ 'image_width' ], $onlineUser[ 'image_height' ], $onlineUser[ 'user_name' ], IMAGE_CROPPED_100x100 );
                ?></div><div><?php
                Element( 'user/url', $onlineUser[ 'user_id' ], $onlineUser[ 'user_subdomain' ] );
                echo $onlineUser[ 'user_name' ];
                ?></div><?php
            }
        }
    }

?>
