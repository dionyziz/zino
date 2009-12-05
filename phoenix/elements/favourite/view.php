<?php
    class ElementFavouriteView extends Element {
        public function Render( tText $subdomain, tText $type, tInteger $pageno ) {
            global $rabbit_settings;
            global $libs;
            global $user;
            global $page;

            $libs->Load( 'favourite' );
            $libs->Load( 'journal/journal' );
            $libs->Load( 'album' );
            $libs->Load( 'store' );
            
            $subdomain = $subdomain->Get();
            $type = $type->Get();
            $pageno = $pageno->Get();

            Element( 'user/subdomainmatch' );
            
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
            
            if ( ctype_upper( substr( $album->Owner->Name, 0, 1 ) ) ) {
                $page->SetTitle( $album->Owner->Name . " Αγαπημένα" );
            }
            else {
                $page->SetTitle( $album->Owner->Name . " αγαπημένα" );
            }
            // Find all user's favourite journals
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $subdomain );

            if ( $theuser->Deleted ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/deleted' );
            }
            if ( Ban::isBannedUser( $theuser->Id ) ) {
                $libs->Load( 'rabbit/helpers/http' );
                return Redirect( 'http://static.zino.gr/phoenix/banned' );
            }

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
                        ?>favourites/journals" class="s1_0025">&nbsp;</a></li>
                        <li<?php
                        if ( $type === TYPE_IMAGE ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url', $theuser->Id, $theuser->Subdomain );
                        ?>favourites/photos" class="s1_0012">&nbsp;</a></li>
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
                                ?>" id="favourite_<?php
									echo $favourite->Id;
								?>"><div>
                                <?php
                                    if( $theuser->Id == $user->Id ) {
                                        ?><a class="fav_delete" href="" onclick="return Favourites.Delete( <?php
                                            echo $favourite->Id;
                                        ?> )"><span class="s1_0007"> </span> Διαγραφή</a> <?php
                                    }
                                ?>
                                <a href="<?php
                                ob_start();
                                Element( 'url', $favourite->Item );
                                echo htmlspecialchars( ob_get_clean() );
                                ?>">
                                <span class="<?php
								switch ( $favourite->Typeid ) {
                                    case TYPE_IMAGE:
                                        ?>s1_0012<?php
                                        break;
                                    case TYPE_JOURNAL
                                        ?>s1_0025<?php
                                        break;
                                    case TYPE_STOREITEM:
                                        //TODO: Style
                                        break;
                                }
								?>">&nbsp;</span><?php
                                switch ( $favourite->Typeid ) {
                                    case TYPE_JOURNAL:
                                        echo htmlspecialchars( $favourite->Item->Title );
                                        ?></a> από <?php
                                        Element( 'user/name', $favourite->Item->Userid, $favourite->Item->User->Name, $favourite->Item->User->Subdomain, true );
                                        break;
                                    case TYPE_IMAGE:
                                        if ( $favourite->Item->Name != '' ) {
                                            ?>"<?php
                                            echo htmlspecialchars( $favourite->Item->Name );
                                            ?>"<?php
                                        }
                                        else if ( $favourite->Item->Albumid == $favourite->Item->User->Egoalbumid ) {
                                            ?>Φωτογραφία <?php
                                            if ( $favourite->Item->Userid == $user->Id ) {
                                                ?>σου<?php
                                            }
                                            else if ( $favourite->Item->User->Gender == 'f' ) {
                                                ?>της <?php
                                            }
                                            else {
                                                ?>του <?php
                                            }
                                            if ( $favourite->Item->Userid != $user->Id ) {
                                                echo htmlspecialchars( $favourite->Item->User->Name );
                                            }
                                        }
                                        else {
                                            ?>Μια εικόνα του Album "<?php
                                            echo htmlspecialchars( $favourite->Item->Album->Name );
                                            ?>"<?php
                                        }
                                        ?><br /><?php
                                            Element( 'image/view' , $favourite->Itemid , $favourite->Item->Userid , $favourite->Item->Width , $favourite->Item->Height , IMAGE_PROPORTIONAL_210x210, '' , $favourite->Item->Name , $favourite->Item->Name , '' , false, 0, 0 , 0 );
                                            ?>
                                        </a><?php
                                        break;
                                    case TYPE_STOREITEM:
                                        if ( $favourite->Item->Name != '' ) {
                                            ?>ZinoSTORE <?php
                                            echo htmlspecialchars( $favourite->Item->Name );
                                        }
                                        ?><br />
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
                $text = '( ' . $favourites->TotalCount() . ' Αγαπημέν' ;
                if ( $favourites->TotalCount() == 1 ) {
                    $text .= 'ο';
                }
                else {
                    $text .= 'α';
                }
                $text .= ' )';
                Element( 'pagify', $pageno, $link, $totalpages, $text );
            ?></div><?php
        }
    }
?>
