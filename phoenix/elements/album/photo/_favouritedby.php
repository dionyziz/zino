<?php
    class ElementAlbumPhotoFavouritedby extends Element {
        public function Render (tInteger $id, tInteger $size ) {
            global $user;
            global $libs;
            global $water;
            global $rabbit_settings;
            
            //$libs->Load( 'favourite' );
            
            $image = New Image( $id );
            $theuser = $image->User;
            
            ?><div class="image_tags" <?php
            $favouritefinder = New FavouriteFinder();
            $favourites = $favouritefinder->FindByEntity( $image );
            if ( count( $favourites ) == 0 ) {
                ?>style="display:none"<?php
            }
            ?>><?php
                if ( ( count( $favourites ) <= $size ) || ( $size == -1 ) ) {
                    $size = count( $favourites );
                }
                else {
                    $size = $size - 2; //prints total $size - 2 entries, $size - 3 users and the "% more"
                }
                for( $i = 0; $i < $size; ++$i ) {
                    ?><div><?php
                    if ( ( count( $favourites ) > $size ) && ( $i == $size - 1 ) ) {
                        ?><a href="" onclick="PhotoView.completeFav();"><?php
                        echo count( $favourites ) - $size;
                        ?> άλλοι</a></div><?php
                        break;
                    }
                    if ( $favourites[ $i ]->User->Gender == 'f' ) {
                        ?>η <?php
                    }
                    else {
                        ?>ο <?php
                    }
                    Element( 'user/name', $favourites[ $i ]->User->Id, $favourites[ $i ]->User->Name, $favourites[ $i ]->User->Subdomain, true );
                    if ( $i == $size -2 ) {
                        ?> και <?php
                    }
                    else if( $i < $size -2 ) {
                        ?>, <?php
                    }
                    ?></div><?php
                }
                if ( $size == 1 ) {
                    ?> έχει<?php
                }
                else {
                    ?> έχουν<?php
                }
                ?> τη φωτογραφία στα αγαπημένα.</div><?php
        }
    }
?>