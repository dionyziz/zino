<?php    
    class ElementFrontpageShoutboxRecent extends Element {
        protected $mPersistent = array( 'shoutboxseq' );
        
        public function Render( $shoutboxseq ) {
            global $libs;
            
            $libs->Load( 'shoutbox' );

            $finder = New ShoutboxFinder();
            $shouts = $finder->FindLatest( 0 , 7 );
            foreach ( $shouts as $shout ) {
                Element( 'shoutbox/view' , $shout , false );
            }
        }
    }
?>
