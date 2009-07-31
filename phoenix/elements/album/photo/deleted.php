<?php

    class ElementAlbumPhotoDeleted extends Element {
        public function Render( User $theuser ) {
            global $user;

            if ( $user->Id === $theuser->Id ) {
                ?>Έχεις διαγράψει αυτή τη φωτογραφία. Ανέβασε κάποια άλλη!<?php
                Element( 'user/profile/easyupload' );
                return;
            }

            ?>Αυτή η φωτογραφία έχει διαγραφεί.<?php
        }
    }

?>
