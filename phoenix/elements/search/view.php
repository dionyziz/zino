<?php
    class ElementSearchView extends Element {
        public function Render( tInteger $pageno ) {
            global $xc_settings;

            $pageno = $pageno->Get();
            ?><div id="search"><?php
            Element( 'search/options' );
            ?></div><?php
                // Get $users by a finder using $users_per_page, $pageno in the LIMIT statement.
                $users = array();
                $users[] = New User( 1 );
                $users[] = New User( 791 );
                $users_per_page = 1;
            Element( 'user/list', $users );
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            // Change the link
            $link = $xc_settings[ 'webaddress' ] . "?p=search&pageno=";
            $pages = count( $users )/$users_per_page;
            Element( 'pagify', $pageno, $link, $pages );
        }
    }
?>
