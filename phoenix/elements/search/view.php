<?php
    class ElementSearchView extends Element {
        public function Render(
            tInteger $minage, tInteger $maxage, tInteger $placeid, tText $gender, tText $sexual, tText $name,
            tInteger $limit, tInteger $pageno
        ) {
            global $xc_settings;
            global $libs;

            $libs->Load( 'user/search' );

            $minage = $minage->Get();
            $maxage = $maxage->Get();
            $placeid = $placeid->Get();
            $gender = $gender->Get();
            $sexual = $sexual->Get();
            $name = $name->Get();
            $pageno = $pageno->Get();
            $limit = 25;
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
            $offset = ( $pageno - 1 ) * $limit;
            ?><div id="search"><?php
            $location = New Place( $placeid );
            Element( 'search/options',
                $minage, $maxage, $location, $gender, $sexual, $name,
                $offset, $limit
            );
            ?></div><?php
            // Get $users by a finder using $users_per_page, $pageno in the LIMIT statement.
            $finder = New UserSearch();
            $users = $finder->FindByDetails( $minage, $maxage, $location, $gender, $sexual, '', $offset, $limit );
            /*
            Element( 'user/list', $users );
            // Change the link
            $link = $xc_settings[ 'webaddress' ] . "?p=search&pageno=";
            $pages = $finder->FoundRows() / $limit;
            Element( 'pagify', $pageno, $link, $pages );
            */
        }
    }
?>
