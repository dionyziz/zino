<?php 

	class ElementUserProfileSidebarLook extends Element {
        public function Render( $theuser ) {
            $showgender = $showweight = $showheight = false;
            if ( $theuser->Gender != '-' ) {
                $showgender = true;
            }
            if ( $theuser->Profile->Weight >= -3 ) {
                $showweight = true;
            }
            if ( $theuser->Profile->Height >= -3 ) {
                $showheight = true;
            }
            ?><ul><?php
                if ( $showgender ) {
                    ?><li><?php
                    Element( 'user/trivial/gender' , $theuser->Gender );
                    ?></li><?php
                }
                if ( ( $showgender && $showweight ) || ( $showgender && !$showweight && $showheight ) ) {
                    ?><li class="dot">·</li><?php
                }
                if ( $showheight ) {
                    ?><li><?php
                    Element( 'user/trivial/height' , $theuser->Profile->Height );
                    ?></li><?php
                }
                if ( $showweight && $showheight ) {
                    ?><li class="dot">·</li><?php
                }
                if ( $showweight ) {
                    ?><li><?php
                    Element( 'user/trivial/weight' , $theuser->Profile->Weight );
                    ?></li><?php
                }
            ?></ul><?php
        }
    }
?>
