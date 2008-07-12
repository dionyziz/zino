<?php

	class ElementUserSettingsPersonalGender extends Element {
        public function Render() {
            global $user;
            
            ?><select><?php
                $genders = array( '-' , 'm' , 'f' );
                foreach( $genders as $gender ) {
                    ?><option value="<?php
                    echo $gender;
                    ?>"<?php
                    if ( $user->Gender == $gender ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'user/trivial/gender' , $gender );
                    ?></option><?php
                }
            ?></select><?php
        }
    }
?>
