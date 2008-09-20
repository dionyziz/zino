<?php
    class ElementFavouriteView extends Element {
        public function Render( tText $subdomain, tInteger $type ) {
            global $rabbit_settings;
            global $libs;

            $libs->Load( 'favourite' );
            $libs->Load( 'journal' );
            
            $subdomain = $subdomain->Get();
            $type = $type->Get();

            switch ( $type ) {
                case TYPE_JOURNAL:
                case TYPE_IMAGE:
                    break;
                default:
                    $type = false; // all
            }
            
            // Find all user's favourite journals
            $userfinder = New UserFinder();
            $theuser = $userfinder->FindBySubdomain( $subdomain );
            $favfinder = New FavouriteFinder();
            $favourites = $favfinder->FindByUserAndType( $theuser, false );

            ?><div id="favourites">
                <h2>Αγαπημένα</h2>
                <div style="padding: 5px 0 0 20px;"><a href="">&laquo;Επιστροφή στο προφίλ</a></div>
                <div class="list">
                    <ul class="favcategories">
                        <?php
                        /*
                           <li><a href=""><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>chart_bar-trans.png" alt="Δημοσκοπήσεις" title="Δημοσκοπήσεις" /></a></li>
                        */
                        ?>
                        <li><a href=""><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>book.png" alt="Ημερολόγια" title="Ημερολόγια" /></a></li>
                        <li><a href=""><img src="<?php
                        echo $rabbit_settings[ 'imagesurl' ];
                        ?>photo.png" alt="Εικόνες" title="Εικόνες" /></a></li>
                        <li class="selected" style="width:40px;"><a href="">Όλα</a></li>
                    </ul>
                    <div class="eof"></div>
                    <ul class="events"><?php
                        foreach ( $favourites as $favourite ) {
                            switch ( $favourite->Typeid ) {
                                case TYPE_POLL:
                                    ?><li class="poll">
                                        <div><a href="">Πόσες φορές τη βδομάδα βαράς μαλακία;</a> από <a href="">dionyziz</a></div>
                                    </li><?php
                                    break;
                                case TYPE_JOURNAL:
                                    ?><li class="journal">
                                        <div><a href="">MacGuyver sandwich</a> από <a href="">Izual</a></div>
                                    </li><?php
                                    break;
                                case TYPE_IMAGE:
                                    ?><li class="photo">
                                        <div>
                                            <a href="">Γαμάτος ουρανοξύστης από Izual<br />
                                                <img src="images/ph3.jpg" alt="Γαμάτος ουρανοξύστης" title="Γαμάτος ουρανοξύστης" />
                                            </a>
                                        </div>
                                    </li><?php
                                    break;
                            }
                        }
                    ?></ul>
                </div>
            </div><?php

            // print what you have found
            foreach ( $favourites as $value ) {
                echo "id: " . $value->Itemid;
            }
        }
    }
?>
