<?php

	class ElementUserSettingsPersonalPlace extends Element {
        public function Render() {
            global $user;
            
            $finder = New PlaceFinder();
            $places = $finder->FindAll();
            ?><select>
                <option value="-1"<?php
                if ( $user->Profile->Placeid == 0 ) {
                    ?> selected="selected"<?php
                }
                ?>>-</option><?php
                foreach( $places as $place ) {
                    ?><option value="<?php
                    echo $place->Id;
                    ?>"<?php
                    if ( $user->Profile->Placeid == $place->Id ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'user/trivial/place' , $place );
                    ?></option><?php
                }
            ?></select><?php
        }
    }
?>
