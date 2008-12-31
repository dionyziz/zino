<?php
    class ElementiPhoneShoutboxList extends Element {
        public function Render() {
            global $libs;

            $libs->Load( 'shoutbox' );

            $finder = New ShoutboxFinder();
            $shouts = $finder->FindLatest( 0, 7 );

            foreach ( $shouts as $shout ) {
                Element( 'iphone/shoutbox/view', $shout );
            }
        }
    }
?>
