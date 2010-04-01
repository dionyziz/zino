<?php
    class ElementUserName extends Element {
        //protected $mPersistent = array( 'theuserid' , 'link' );
        
		public function Render( $theuserid , $theusername , $theusersubdomain, $link = true ) {
            if ( !$link ) {
                echo htmlspecialchars( str_replace( array( 's', 'S' ), array( 'c', 'C' ), $theusername ) );
            }
            else {
                ?><a href="<?php
                Element( 'user/url' , $theuserid , $theusersubdomain );
                ?>"><?php
                echo htmlspecialchars( str_replace( array( 's', 'S' ), array( 'c', 'C' ), $theusername ) );
                ?></a><?php
            }
        }
    }
?>
