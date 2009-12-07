<?php
    class ElementDeveloperShoutboxList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;

            $pageno = $pageno->Get();

            if ( $pageno <= 0 ) {
                $pageno = 1;
            }

            $libs->Load( 'chat/message' );

            $finder = New ShoutboxFinder();
            $shouts = $finder->FindByChannel( 0, 20 * ( $pageno - 1 ), 20 )
            ?><div class="shoutbox">
                <h2>Συζήτηση</h2>
                <div class="comments"><?php
                    foreach ( $shouts as $shout ) {
                        Element( 'developer/shoutbox/view' , $shout , false );
                    }
                ?></div>
            </div>
            <div class="eof"></div><?php
            Element( 'developer/pagify', $pageno, '?p=shoutbox&pageno=', ceil( $finder->Count() / 20 ) );
        }
    }
?>
