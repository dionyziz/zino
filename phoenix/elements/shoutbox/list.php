<?php
    class ElementShoutboxList extends Element {
        public function Render( tInteger $pageno ) {
            global $libs;

            $pageno = $pageno->Get();

            if ( $pageno <= 0 ) {
                $pageno = 1;
            }

            $libs->Load( 'shoutbox' );

            $finder = New ShoutboxFinder();
            $shouts = $finder->FindLatest( 20 * ( $pageno - 1 ), 20 )
            ?><div class="shoutbox">
                <h2>Συζήτηση</h2>
                <div class="comments"><?php
                    foreach ( $shouts as $shout ) {
                        Element( 'shoutbox/view' , $shout , false );
                    }
                ?></div>
            </div>
            <div class="eof"></div><?php
            Element( 'pagify', $pageno, '?p=shoutbox&pageno=', ceil( $finder->Count() / 20 ) );
        }
    }
?>
