<?php

    class ElementUserSections extends Element {
        public function Render( $section , $theuser ) {
            global $user;
            global $libs;
            
            $libs->Load( 'user/count' );
            $libs->Load( 'image/image' );
            
            ?><div class="sx_0009 usersections">
                <ul><?php
                    if ( $theuser->Count->Images > 0 || $theuser->Id == $user->Id ) {
                        ob_start();
                        ?><li<?php
                        if ( $section == 'album' ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                        ?>albums">Albums</a></li><?php
                        $linklist[] = ob_get_clean();
                    }
                    if ( $theuser->Count->Polls > 0 || $theuser->Id == $user->Id ) {
                        ob_start();
                        ?><li<?php
                        if ( $section == 'poll' ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                        ?>polls">Δημοσκοπήσεις</a></li><?php
                        $linklist[] = ob_get_clean();
                    }
                    if ( $theuser->Count->Journals > 0 || $theuser->Id == $user->Id ) {
                        ob_start();
                        ?><li<?php
                        if ( $section == 'journal' ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                        ?>journals">Ημερολόγιο</a></li><?php
                        $linklist[] = ob_get_clean();
                    }
                    //TODO: run a script to count every user's favourites then remove the true condition
                    if ( true || $theuser->Count->Favourites > 0 || $theuser->Id == $user->Id ) {
                        ob_start();
                        ?><li<?php
                        if ( $section == 'favourites' ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                        ?>favourites">Αγαπημένα</a></li><?php
                        $linklist[] = ob_get_clean();
                    }
                    if ( $theuser->Count->Relations > 0 || $theuser->Id == $user->Id ) {
                        ob_start();
                        ?><li<?php
                        if ( $section == 'relations' ) {
                            ?> class="selected"<?php
                        }
                        ?>><a href="<?php
                        Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                        ?>friends">Φίλοι</a></li><?php
                        $linklist[] = ob_get_clean();
                    }
                    while ( $link = array_shift( $linklist ) ) {
                        echo $link;
                        if ( !empty( $linklist ) ) {
                            ?><li>·</li><?php
                        }
                    }
                    ?>
                </ul>
                <a href="<?php
                    Element( 'user/url' , $theuser->Id , $theuser->Subdomain );
                    ?>"><?php
                    Element( 'user/avatar' , $theuser->Avatarid , $theuser->Id , $theuser->Avatar->Width , $theuser->Avatar->Height , $theuser->Name , 150 , '' , '' , false , 0 , 0 );
                    ?><span class="name"><?php
                    echo $theuser->Name;
                    ?></span>
                </a>
            </div><?php
        }
    }
?>
