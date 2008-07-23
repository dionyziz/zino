<?php
    
    class ElementFrontpageShoutboxList extends Element {
        public function Render() {
            global $user;
            global $libs;
            $libs->Load( 'shoutbox' );
            
            $finder = New ShoutboxFinder();
            $shouts = $finder->FindLatest( 0 , 7 )
            ?><div class="shoutbox">
                <h2>Συζήτηση</h2>
                <div class="comments"><?php
                    if ( $user->Exists() && $user->HasPermission( PERMISSION_SHOUTBOX_CREATE ) ) {
                        Element( 'shoutbox/reply' );
                    }
                    foreach ( $shouts as $shout ) {
                        Element( 'shoutbox/view' , $shout , false );
                    }
                    Element( 'shoutbox/view'  , false , true );
                ?></div>
            <div class="eof"></div>
            <div class="more"><a href="shouts" class="button">Όλες οι συζητήσεις&raquo;</a></div>
            </div><?php
        }
    }
?>
