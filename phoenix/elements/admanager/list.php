<?php
    /* 
        Developer: Dionyziz
    */
    
    class ElementAdManagerList extends Element {
        public function Render() {
            global $user;
            global $libs;
            global $page;
            
            if ( !$user->Exists() ) {
                return Redirect( '?p=admanager/create&error=notloggedin' );
            }
            
            $libs->Load( 'admanager' );
            
            $adfinder = New AdFinder();
            $ads = $adfinder->FindByUser( $user );
            if ( empty( $ads ) ) {
                return Redirect( '?p=admanager/create&error=noads' );
            }
            
            $page->SetTitle( 'Διαχείριση διαφημίσεων' );
            
            ?><div class="buyad">
                <h2 class="ad">Έχετε <?php
                echo count( $ads );
                ?> ενεργ<?php
                if ( count( $ads ) == 1 ) {
                    ?>ή<?php
                }
                else {
                    ?>ές<?php
                }
                ?> διαφημιστικ<?php
                if ( count( $ads ) == 1 ) {
                    ?>ή<?php
                }
                else {
                    ?>ές<?php
                }
                ?> καμπάνι<?php
                if ( count( $ads ) == 1 ) {
                    ?>α<?php
                }
                else {
                    ?>ες<?php
                }
                ?></h2>
                <table class="manager">
                    <thead>
                        <tr>
                            <th>Διαφήμιση</th>
                            <th>Target group</th>
                            <!-- <th>Budget</th> -->
                            <th>Προβολές που απομένουν</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr class="create">
                            <td colspan="4" class="last">
                                <div>
                                    <a href="?p=admanager/create">Δημιουργία νέας καμπάνιας</a>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                    <tbody><?php
                    foreach ( $ads as $ad ) {
                        ?><tr>
                            <td><div class="ads"><?php
                                Element( 'admanager/view', $ad, false );
                                ?></div>
                                <a class="edit" href="?p=admanager/create&amp;adid=<?php
                                echo $ad->Id;
                                ?>">Επεξεργασία</a>
                            </td>
                            <td><?php
                                $age = '';
                                if ( $ad->Minage > 0 && $ad->Maxage == 0 ) {
                                    $age = 'τουλάχιστον ' . $ad->Minage . ' ετών';
                                }
                                else if ( $ad->Minage == 0 && $ad->Maxage > 0 ) {
                                    $age = 'το πολύ ' . $ad->Maxage . ' ετών';
                                }
                                else if ( $ad->Minage > 0 && $ad->Maxage > 0 ) {
                                    $age = $ad->Minage . ' - ' . $ad->Maxage . ' ετών';
                                }
                                
                                $sex = '';
                                if ( $ad->Sex == 1 ) {
                                    $sex = 'άντρες';
                                    if ( $ad->Maxage != 0 && $ad->Maxage < 25 ) {
                                        $sex = 'αγόρια';
                                    }
                                }
                                else if ( $ad->Sex == 2 ) {
                                    $sex = 'γυναίκες';
                                    if ( $ad->Maxage != 0 && $ad->Maxage < 25 ) {
                                        $sex = 'κορίτσια';
                                    }
                                }
                                
                                $location = '';
                                $places = $ad->Places;
                                if ( count( $places ) ) {
                                    $placenames = array();
                                    foreach ( $places as $place ) {
                                        $placenames[] = $place->Nameaccusative;
                                    }
                                    if ( count( $placenames ) > 1 ) {
                                        $placenames[] = 'και ' . array_pop( $placenames );
                                    }
                                    if ( count( $placenames ) == 2 ) {
                                        $location = implode( ' ', $placenames );
                                    }
                                    else {
                                        $location = implode( ', ', $placenames );
                                    }
                                    $location = 'από ' . $location;
                                }
                                
                                if ( empty( $age ) && empty( $sex ) && empty( $location ) ) {
                                    $demographics = 'Χωρίς προτιμήσεις';
                                }
                                else {
                                    $demographics = implode( ' ', array( $sex, $age, $location ) );
                                    $demographics = mb_strtoupper( mb_substr( $demographics, 0, 1 ) ) 
                                                    . mb_substr( $demographics, 1 );
                                }
                                
                                echo htmlspecialchars( $demographics );
                            ?> - <a class="renew" href="?p=admanager/demographics&amp;adid=<?php
                            echo $ad->Id;
                            ?>">Αλλαγή</a></td>
                            <!-- <td>3,520€</td> -->
                            <td class="last<?php
                            if ( !$ad->Pageviewsremaining
                                 || ( $ad->Dailypageviews && $ad->Pageviewsremaining / $ad->Dailypageviews <= 5 )
                                 || !$ad->IsActive() ) {
                                ?> soon<?php
                            }
                            ?>"><?php
                            if ( !$ad->IsActive() ) {
                                ?>Αναμένεται ενεργοποίηση<?php
                            }
                            else if ( $ad->Pageviewsremaining ) {
                                echo $ad->Pageviewsremaining;
                            }
                            else {
                                ?>Έχει εξαντληθεί<?php
                            }
                            // Ανενεργή: Αναμένεται πληρωμή
                            if ( $ad->IsActive() ) {
                                /* ?> - <a class="renew" href="" onclick="return false;">Ανανέωση συνδρομής</a><?php */
                            }
                            ?></td>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div><?php
        }
    }
?>
