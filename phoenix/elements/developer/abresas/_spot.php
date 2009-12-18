<?php

    class ElementDeveloperAbresasSpot extends Element {
        public function Render() {
            global $libs;
            global $user;

            $libs->Load( 'research/spot' );

            $content = Spot::GetContent( $user, 8, 5, 4 );

            ?><ul style="list-style-type: none; margin: 20px 0 0 20px; padding: 0;"><?php
            foreach ( $content as $object ) {
                ?><li><?php
                if ( $object instanceof Image ) {
                    $image = $object;
                    ?>Φωτογραφία <a href="<?php
                    ob_start();
                    Element( 'url', $image );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>"><?php
                    if ( !empty( $image->Name ) ) {
                        echo $image->Name;
                    }
                    else {
                        echo "(ανώνυμη)";
                    }
                    ?></a> του <a href="<?php
                    ob_start();
                    Element( 'url', $image->User );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>"><?php
                    echo $image->User->Name;
                    ?></a><?php
                }
                else if ( $object instanceof Journal ) {
                    $journal = $object;
                    ?>Ημερολόγιο <a href="<?php
                    ob_start();
                    Element( 'url', $journal );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>"><?php
                    echo $journal->Title;
                    ?></a> του <a href="<?php
                    ob_start();
                    Element( 'url', $journal->User );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>"><?php
                    echo $journal->User->Name;
                    ?></a><?php
                }
                else {
                    $poll = $object;
                    ?>Δημοσκόπηση <a href="<?php
                    ob_start();
                    echo Element( 'url', $poll );
                    echo htmlspecialchars( ob_get_clean() );
                    ?>"><?php
                    echo $poll->Question;
                    ?></a> του <a href="<?php
                    ob_start();
                    echo Element( 'url', $poll->User );
                    echo htmlspecialchars( ob_get_clean() );
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
