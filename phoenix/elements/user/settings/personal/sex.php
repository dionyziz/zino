<?php

	class ElementUserSettingsPersonalSex extends Element {
        public function Render( $selected , $gender ) {		
            ?><select><?php
            $sexs = array( '-' , 'straight' , 'gay' , 'bi' );
            foreach ( $sexs as $sex ) {
                ?><option value="<?php
                echo $sex;
                ?>"<?php
                if ( $selected == $sex ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/sex' , $sex , $gender );
                ?></option><?php
            }
            ?></select><?php
        }
    }
?>
