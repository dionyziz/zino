<?php
    
    class ElementDeveloperUserSettingsPersonalFavquote extends Element {
        public function Render() {
            global $user;

            ?><input type="text" value="<?php
            echo htmlspecialchars( $user->Profile->Favquote );
            ?>" /><?php
        }
    }
?>
