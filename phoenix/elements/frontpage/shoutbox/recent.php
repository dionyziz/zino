<?php    
    class ElementFrontpageShoutboxRecent extends Element {
        protected $mPersistent = array( 'shoutboxseq' );
        
        public function Render( $shoutboxseq ) {
            global $libs;
            
            $libs->Load( 'chat/message' );

            $finder = New ShoutboxFinder();
            $shouts = $finder->FindByChannel( 0, 0, 7 );
            foreach ( $shouts as $shout ) {
                Element( 'shoutbox/view' , $shout , false );
            }
        }
    }
?>
