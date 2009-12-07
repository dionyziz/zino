<?php
    
    class ElementDeveloperUserSettingsPersonalSlogan extends Element {
        public function Render() {
            global $user;
            
            ?><input type="text" value="<?php
            echo htmlspecialchars( $user->Profile->Slogan );
            ?>" /><?php
        }
    }
?>
