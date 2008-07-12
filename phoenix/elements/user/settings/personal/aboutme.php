<?php

	class ElementUserSettingsPersonalAboutme extends Element {
        public function Render() {
            global $user;
            
            ?><textarea><?php
            echo htmlspecialchars( $user->Profile->Aboutme );
            ?></textarea><?php
        }
    }
?>
