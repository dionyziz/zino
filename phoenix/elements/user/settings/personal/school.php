<?php
    class ElementUserSettingsPersonalSchool extends Element {
        public function Render( $placeid, $typeid ) {
            return;
            global $user;
            
            if ( ( $placeid > 0 ) && ( $typeid >= 1 && $typeid <= 6 ) ) {
                $finder = New SchoolFinder();
                $schools = $finder->Find( $placeid, $typeid );
                if ( count( $schools ) > 0 ) {    
                    ?><select>
                        <option value="-1"<?php
                        if ( $user->Profile->School->Id == 0 ) {
                            ?> selected="selected"<?php
                        }
                        ?>>-</option><?php
                        foreach( $schools as $school ) {
                            ?><option value="<?php
                            echo $school->Id;
                            ?>"<?php
                            if ( $user->Profile->School->Id == $school->Id ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            Element( 'user/trivial/school', $school );
                            ?></option><?php
                        }
                    ?></select><?php
                }
                else {
                    ?><span>Δεν υπάρχουν εκπαιδευτικά ιδρύματα στην περιοχή</span><?php
                }
            }
        }
    }

?>
