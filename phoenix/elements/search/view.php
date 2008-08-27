<?php
    class ElementSearchView extends Element {
        public function Render( $pageno ) {
            global $xc_settings;

            ?><div id="search"><?php
            Element( 'search/options' );
            ?></div><?php
                // Get $users by a finder using $users_per_page, $pageno in the LIMIT statement.
                $users = array();
                $users[] = New User( 1 );
                $users[] = New User( 791 );
                $users_per_page = 2;
            Element( 'user/list', $users );
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            // Change the link
            Element( 'pagify', $pageno, $xc_settings[ 'webaddress'] . "?p=search&pageno=", $users/$users_per_page );
        }
    }
?>
