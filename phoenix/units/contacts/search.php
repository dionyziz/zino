<?php
    function UnitContactsSearch(
        tInteger $minage, tInteger $maxage,
        tInteger $placeid, tText $gender,
        tText $orientation, tText $name,
        tInteger $limit, tInteger $pageno
    ) {
        global $libs;
        global $user;
        if ( !$user->Exists() ) {
            return false;
        }
        
        global $xc_settings;
        global $libs;

        $libs->Load( 'user/search' );

        $minage = $minage->Get();
        $maxage = $maxage->Get();
        $placeid = $placeid->Get();
        $gender = $gender->Get();
        $orientation = $orientation->Get();
        $name = $name->Get();
        $pageno = $pageno->Get();
        $limit = 25;
        if ( $pageno <= 0 ) {
            $pageno = 1;
        }
        $offset = ( $pageno - 1 ) * $limit;
        $searching = $minage > 0 || $maxage > 0 || $gender == 'm' || $gender == 'f' || $orientation !== '' || $location->Exists();
        if ( $searching ) {
            // Get $users by a finder using $users_per_page, $pageno in the LIMIT statement.
            $finder = New UserSearch();
            $users = $finder->FindByDetails( $minage, $maxage, $location, $gender, $orientation, '', $offset, $limit );
            if ( !count( $users ) ) {
                ?>$( '#searchTag .people' ).html( '<div style="text-align:center;margin-bottom:20px"><strong>Δεν βρέθηκαν άτομα με τα κριτήρια αναζήτησής σου.</strong><br />Δοκίμασε να αφαιρέσεις κάποιο κριτήριο ή να ψάξεις με πιο γενικούς όρους.</div>' );
                $( '#searchTag .pagify' ).html( '' );<?php
            }
            else{
                ?>$( '#searchTag .people' ).html( '<?php
                    Element( 'user/list', $users );
                ?>' );
                $( '#searchTag .pagify' ).html( '<?php
                    $args = compact( 'minage', 'maxage', 'placeid', 'gender', 'orientation' );
                    $searchargs = array();
                    foreach ( $args as $key => $arg ) {
                        $searchargs[] = $key . '=' . urlencode( $arg );
                    }
                    $link = $xc_settings[ 'webaddress' ] . "?p=search&" . implode( '&', $searchargs ) . "&pageno=";
                    $pages = $finder->FoundRows() / $limit;
                    Element( 'pagify', $pageno, $link, $pages );
                ?>' );<?php
            }
        }
    }

?>
