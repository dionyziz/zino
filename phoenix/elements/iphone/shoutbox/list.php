<?php
    class ElementiPhoneShoutboxList extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'shoutbox' );

            $finder = New ShoutboxFinder();
            $shouts = $finder->FindLatest( 0, 7 );

            ?><div class="shoutbox"><?php
                Element( 'iphone/shoutbox/new' );
                ?><ul><?php
                foreach ( $shouts as $shout ) {
                    ?><li><?php
                    Element( 'iphone/shoutbox/view', $shout );
                    ?></li><?php
                }
                ?></ul>
            </div><?php
        }
    }
?>
