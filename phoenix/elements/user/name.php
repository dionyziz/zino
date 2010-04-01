<?php
    class ElementUserName extends Element {
        //protected $mPersistent = array( 'theuserid' , 'link' );
        
		public function Render( $theuserid , $theusername , $theusersubdomain, $link = true ) {
            if ( !$link ) {
                echo htmlspecialchars( $theusername );
            }
            else {
                ?><a href="<?php
                Element( 'user/url' , $theuserid , $theusersubdomain );
                ?>"><?php
                echo htmlspecialchars( $theusername );
                ?></a><?php
            }
        }
    }
?>
