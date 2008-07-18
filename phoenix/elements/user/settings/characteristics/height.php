<?php

	class ElementUserSettingsCharacteristicsHeight extends Element {
        public function Render() {
            global $user;
            
            ?><select>
                <option value="-3"<?php
                if ( $user->Profile->Height == -1 ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/height' , -1 );
                ?></option>
                <option value="-2"<?php
                if ( $user->Profile->Height == -2 ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/height' , -2 );
                ?></option><?php
                for ( $i = 120; $i <= 220; ++$i ) {
                    ?><option value="<?php
                    echo $i;
                    ?>"<?php
                    if ( $user->Profile->Height == $i ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'user/trivial/height' , $i );
                    ?></option><?php
                }
                ?><option value="-1"<?php
                if ( $user->Profile->Height == -3 ) {
                    ?> selected="selected"<?php
                }
                ?>><?php
                Element( 'user/trivial/height' , -3  );
                ?></option>
            </select><?php
        }
    }
?>
