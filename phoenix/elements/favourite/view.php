<?php
    class ElementFavouriteView extends Element {
        public function Render( tText $subdomain, tText $type, tInteger $pageno ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            global $page;

            $libs->Load( 'favourite' );
            $libs->Load( 'journal' );
            
            $subdomain = $subdomain->Get();
            $type = $type->Get();
            $pageno = $pageno->Get();

            if ( $pageno < 1 ) {
                $pageno = 1;
            }
            $limit = 25;
            $offset = ( $pageno - 1 ) * $limit;

            switch ( $type ) {
                case 'journals':
                    $type = TYPE_JOURNAL;
                    break;
                case 'photos':
                    $type = TYPE_IMAGE;
                    break;
                default:
                    $type = false; // all
            }
            
            if ( strtoupper( substr( $album->Owner->Name, 0, 1 ) ) == substr( $album->Owner->Name, 0, 1 ) ) {
                $page->SetTitle( $album->Owner->Name . " Αγαπημένα" );
            }
            else {
                $page->SetTitle( $album->Owner->Name . " αγαπημένα" );
            }
            // Find all user's favourite journals
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $subdomain );
            $favfinder = New FavouriteFinder();
            $favourites = $favfinder->FindByUserAndType( $theuser, $type, $offset, $limit );

            Element( 'user/sections', 'favourites', $theuser );

            ?><div id="fav">
                <div class="list">
                    <ul class="favcategories">
                        <li<?php
                        if ( $type === TYPE_JOURNAL ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites/journals" class="s_book">&nbsp;</a></li>
                        <li<?php
                        if ( $type === TYPE_IMAGE ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites/photos" class="s_photo">&nbsp;</a></li>
                        <li<?php
                        if ( $type === false ) {
                            ?> class="selected"<?php
                        }
                        ?> style="width:40px">
                        <a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites">Όλα</a></li>
                    </ul>
                    <div style="clear:right"></div>
                    <ul class="events"><?php
                        $i = 0;
                        if ( !count( $favourites ) ) {
                            ?><li class="last"><div><?php
                            if ( $theuser->Id == $user->Id ) {
                                ?>Δεν έχεις κάποια αγαπημένα.<br />
                                Μπορείς να προσθέσεις μία φωτογραφία ή ένα ημερολόγιο που σου αρέσει στα αγαπημένα σου όταν το βλέπεις.<?php
                            }
                            else {
                                switch ( $theuser->Gender ) {
                                    case 'f':
                                        ?>Η <?php
                                        break;
                                    case 'm':
                                    default:
                                        ?>O <?php
                                }
                                Element( 'user/name', $theuser->Id, $theuser->Name, $theuser->Subdomain, true );
                                ?> δεν έχει αγαπημένα.<br />
                                Πρότεινέ <?php
                                switch ( $theuser->Gender ) {
                                    case 'f':
                                        ?>της<?php
                                        break;
                                    case 'm':
                                    default:
                                        ?>του<?php
                                }
                                ?> κάποιο ημερολόγιο ή φωτογραφία για να το προσθέσει.<?php
                            }
                            ?></div></li><?php
                        }
                        else {
                            foreach ( $favourites as $favourite ) {
                                ?><li class="<?php
                                if ( $i == count( $favourites ) - 1 ) {
                                    ?>last <?php
                                }
                                switch ( $favourite->Typeid ) {
                                    case TYPE_IMAGE:
                                        ?>photo<?php
                                        break;
                                    case TYPE_JOURNAL
                                        ?>journal<?php
                                        break;
                                }
                                ?>"><div><a href="<?php
                                ob_start();
                                Element( 'url', $favourite->Item );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>"><span class="<?php
								switch ( $favourite->Typeid ) {
                                    case TYPE_IMAGE:
                                        ?>s_photo<?php
                                        break;
                                    case TYPE_JOURNAL
                                        ?>s_book<?php
                                        break;
                                }
								?>">&nbsp;</span><?php
                                switch ( $favourite->Typeid ) {
                                    case TYPE_JOURNAL:
                                        echo htmlspecialchars( $favourite->Item->Title );
                                        ?></a> από <?php
                                        Element( 'user/name', $favourite->Item->User->Id, $favourite->Item->User->Name, $favourite->Item->User->Subdomain, true );
                                        break;
                                    case TYPE_IMAGE:
                                        if ( $favourite->Item->Name != '' ) {
                                            ?>"<?php
                                            echo htmlspecialchars( $favourite->Item->Name );
                                            ?>"<?php
                                        }
                                        else if ( $favourite->Item->Album->Id == $favourite->Item->User->Egoalbumid ) {
                                            ?>Φωτογραφία <?php
                                            if ( $favourite->Item->User->Id == $user->Id ) {
                                                ?>σου<?php
                                            }
                                            else if ( $favourite->Item->User->Gender == 'f' ) {
                                                ?>της <?php
                                            }
                                            else {
                                                ?>του <?php
                                            }
                                            if ( $favourite->Item->User->Id != $user->Id ) {
                                                echo htmlspecialchars( $favourite->Item->User->Name );
                                            }
                                        }
                                        else {
                                            ?>Μια εικόνα του Album "<?php
                                            echo htmlspecialchars( $favourite->Item->Album->Name );
                                            ?>"<?php
                                        }
                                        ?><br /><?php
                                            Element( 'image/view' , $favourite->Item->Id , $favourite->Item->User->Id , $favourite->Item->Width , $favourite->Item->Height , IMAGE_PROPORTIONAL_210x210, '' , $favourite->Item->Name , $favourite->Item->Name , '' , false, 0, 0 , 0 );
                                            ?>
                                        </a><?php
                                        break;
                                }
                                ?></div></li><?php
                                ++$i;
                            }
                        }
                    ?></ul>
                </div><?php
                ob_start();
                Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                $link = ob_get_clean() . 'favourites';
                if ( $type !== false ) {
                    switch ( $type ) {
                        case TYPE_IMAGE:
                            $link .= '/photos';
                            break;
                        case TYPE_JOURNAL:
                            $link .= '/journals';
                            break;
                    }
                }
                $link .= "?pageno=";
                $totalpages = ceil( $favourites->TotalCount() / $limit );
                Element( 'pagify', $pageno, $link, $totalpages );
            ?></div><?php
        }
    }
?>
