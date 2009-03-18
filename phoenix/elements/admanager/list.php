<?php
    class ElementAdManagerList extends Element {
        public function Render() {
            global $user;
            global $libs;
            
            if ( !$user->Exists() ) {
                return Redirect( '?p=ads' );
            }
            
            $libs->Load( 'admanager' );
            
            $adfinder = New AdFinder();
            $ads = $adfinder->FindByUser( $user );
            if ( empty( $ads ) ) {
                return Redirect( '?p=admanager/create' );
            }
            
            ?><table class="manager">
                <thead>
                    <tr>
                        <th>Διαφήμιση</th>
                        <th>Target group</th>
                        <th>Budget</th>
                        <th>Προβολές που απομένουν</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr class="create">
                        <td colspan="4" class="last">
                            <div>
                                <a href="">Δημιουργία νέας καμπάνιας</a>
                            </div>
                        </td>
                    </tr>
                </tfoot>
                <tbody><?php
                foreach ( $ads as $ad ) {
                    ?><tr>
                        <td><?php
                            Element( 'admanager/view', $ad );
                            ?><a class="edit" href="">Επεξεργασία</a>
                        </td>
                        <td><?php
                            // Αγόρια 13 - 19 ετών από Αθήνα
                            // Χωρίς προτιμήσεις
                            // Τουλάχιστον 16 ετών από Αθήνα, Θεσσαλονίκη, και Πάτρα
                            // Γυναίκες κάτω των 32 ετών από Καρδίτσα και Τρίκαλα
                        ?> - <a class="renew" href="">Αλλαγή</a></td>
                        <td>3,520€</td>
                        <td class="last<?php
                        if ( false ) {
                            ?> soon<?php
                        }
                        ?>"><?php
                        // 176,000
                        // Έχει εξαντληθεί
                        // Ανενεργή: Αναμένεται πληρωμή
                        ?> - <a class="renew" href="">Ανανέωση συνδρομής</a></td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table><?php
        }
    }
?>
