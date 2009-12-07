<?php
    
    class ElementDeveloperUserSettingsCharacteristicsDrinker extends Element {
        public function Render() {
            global $user;
            
            ?><select><?php
                $yesno = array( '-' , 'yes' , 'no' , 'socially' );
                foreach ( $yesno as $answer ) {
                    ?><option value="<?php
                    echo $answer;
                    ?>"<?php
                    if ( $user->Profile->Drinker == $answer ) {
                        ?> selected="selected"<?php
                    }
                    ?>><?php
                    Element( 'developer/user/trivial/yesno' , $answer );
                    ?></option><?php
                }
            ?></select><?php
        }
    }
?>
