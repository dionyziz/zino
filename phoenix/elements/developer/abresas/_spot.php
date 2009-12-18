<?php

    class ElementDeveloperAbresasSpot extends Element {
        public function Render() {
            global $libs;
            global $user;

            $libs->Load( 'research/spot' );

            $content = Spot::GetContent( $user, 8, 5, 4 );

            ?><ul style="list-style-type: none; margin: 10px 0 0 0; padding: 0;"><?php
            foreach ( $content as $object ) {
                ?><li><?php
                if ( $object instanceof Image ) {
                    $image = $object;
                    ?>Φωτογραφία "<?php
                    echo $image->Name;
                    ?>" του <a href="<?php
                    Element( 'url', $image->User );
                    ?>"><?php
                    echo $image->User->Name;
                    ?></a><?php
                }
                else if ( $object instanceof Journal ) {
                    $journal = $object;
                    ?>Ημερολόγιο "<?php
                    echo $journal->Title;
                    ?>" του <a href="<?php
                    Element( 'url', $journal->User );
                    ?>"><?php
                    echo $journal->User->Name;
                    ?></a><?php
                }
                else {
                    $poll = $object;
                    ?>Δημοσκόπηση "<?php
                    echo $poll->Question;
                    ?>" του <a href="<?php
                    Element( 'url', $poll->User );
                    ?>"><?php
                    echo $poll->User->Name;
                    ?></a><?php
                }
                ?></li><?php
            }
            ?></ul><?php
        }
    }

?>
