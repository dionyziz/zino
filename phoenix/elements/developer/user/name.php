<?php
    class ElementDeveloperUserName extends Element {
        //protected $mPersistent = array( 'theuserid' , 'link' );
        
		public function Render( $theuserid , $theusername , $theusersubdomain, $link = true ) {
            if ( !$link ) {
                echo htmlspecialchars( $theusername );
            }
            else {
                ?><a href="<?php
                Element( 'developer/user/url' , $theuserid , $theusersubdomain );
                ?>"><?php
                echo htmlspecialchars( $theusername );
                ?></a><?php
            }
        }
    }
?>
