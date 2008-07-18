<?php

	class ElementUserSettingsCharacteristicsWeight extends Element {
        public function Render() {
            global $user;
            
            ?><select>
                <option value="-3"<?php					
                if ( $user->Profile->Weight == -3 ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/weight' , -3 );
                ?></option>
                <option value="-2"<?php
                if ( $user->Profile->Weight == -2 ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/weight' , -2 );
                ?></option><?php
                for ( $i = 30; $i <= 150; ++$i ) {
                    ?><option value="<?php
                    echo $i;
                    ?>"<?php
                    if ( $user->Profile->Weight == $i ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'user/trivial/weight' , $i );
                    ?></option><?php
                }
                ?><option value="-1"<?php
                if ( $user->Profile->Weight == -1 ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/weight' , -1 );
                ?></option><?php
            ?></select><?php
        }
    }
?>
