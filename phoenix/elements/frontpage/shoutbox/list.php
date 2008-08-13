<?php
    
    class ElementFrontpageShoutboxList extends Element {
        protected $mPersistent = array( 'shoutboxseq' );
        public function Render( $shoutboxseq ) {
            global $user;
            global $libs;
            $libs->Load( 'shoutbox' );
            
            $finder = New ShoutboxFinder();
            $shouts = $finder->FindLatest( 0 , 7 )
            ?><h2>Συζήτηση <span>(<a href="shouts">προβολή όλων</a>)</span></h2>
            <div class="comments"><?php
                if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
                    Element( 'shoutbox/reply' , $user->Id , $user->Avatar->Id , $user );
                }
                foreach ( $shouts as $shout ) {
                    Element( 'shoutbox/view' , $shout , false );
                }
                Element( 'shoutbox/view'  , false , true );
            ?></div>
            <div class="eof"></div><?php
        }
    }
?>
