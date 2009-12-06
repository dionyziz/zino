<?php
    class ElementDeveloperAlbumPhotoFavouritedby extends Element {
        public function Render( $id, $size ) {
            global $user;
            global $water;
            global $rabbit_settings;
            global $libs;

            $libs->Load( 'favourite' );
            $libs->Load( 'image/image' );

            $image = New Image( $id );
            $theuser = $image->User;
            
            $favouritefinder = New FavouriteFinder();
            $favourites = $favouritefinder->FindByEntity( $image, 100 );
            if ( count( $favourites ) == 0 ) {
                return;
            }
            if ( ( count( $favourites ) <= $size ) || ( $size == -1 ) ) {
                $size = count( $favourites );
            }
            else {
                $size = $size - 2; //prints total $size - 2 entries, $size - 3 users and the "% more"
            }
            for( $i = 0; $i < $size; ++$i ) {
                ?><div><?php
                if ( ( count( $favourites ) > $size ) && ( $i == $size - 1 ) ) {
                    ?><a href="" onclick="return PhotoView.completeFav( <?php 
                        echo $id;
                    ?> )"><?php
                    echo count( $favourites ) - $size + 1;
                    ?> άλλοι</a></div><?php
                    break;
                }
                if ( $favourites[ $i ]->User->Gender == 'f' ) {
                    ?>η <?php
                }
                else {
                    ?>ο <?php
                }
                Element( 'user/name', $favourites[ $i ]->Userid, $favourites[ $i ]->User->Name, $favourites[ $i ]->User->Subdomain, true );
                if ( $i == $size -2 ) {
                    ?> και <?php
                }
                else if( $i < $size -2 ) {
                    ?>, <?php
                }
                ?></div><?php
            }
            if ( $size == 1 ) {
                ?> αγαπάει<?php
            }
            else {
                ?> αγαπούν<?php
            }
            ?> αυτή τη φωτογραφία.<?php
        }
    }
?>
