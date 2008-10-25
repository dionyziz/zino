<?php

    class ElementUserSettingsPersonalEducation extends Element {
        public function Render() {
            global $user;
            
            ?><select><?php
                $educations = range( 0 , 6 );
                foreach ( $educations as $education ) {
                    ?><option value="<?php
                    echo $education;
                    ?>"<?php
                    if ( $user->Profile->Education == $education ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'user/trivial/education', $education );
                    ?></option><?php
                }
            ?></select><?php
        }
    }

?>
