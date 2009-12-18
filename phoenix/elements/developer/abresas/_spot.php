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
                    ?>Φωτογραφία <a href="<?php
                    Element( 'url', $image );
                    ?>">"<?php
                    echo $image->Name;
                    ?>"</a> του <a href="<?php
                    Element( 'url', $image->User );
                    ?>"><?php
                    echo $image->User->Name;
                    ?></a><?php
                }
                else if ( $object instanceof Journal ) {
                    $journal = $object;
                    ?>Ημερολόγιο <a href="<?php
                    Element( 'url', $journal );
                    ?>">"<?php
                    echo $journal->Title;
                    ?>"</a> του <a href="<?php
                    Element( 'url', $journal->User );
                    ?>"><?php
                    echo $journal->User->Name;
                    ?></a><?php
                }
                else {
                    $poll = $object;
                    ?>Δημοσκόπηση <a href="<?php
                    Element( 'url', $poll );
                    ?>">"<?php
                    echo $poll->Question;
                    ?>"</a> του <a href="<?php
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
