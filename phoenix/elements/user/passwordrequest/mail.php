<?php
    /* Content-type: text/plain */
    class ElementUserPasswordRequestMail extends Element {
        public function Render( $username, $requestid, $hash ) {
            global $rabbit_settings;
            
            ?>Είμαστε έτοιμοι να αλλάξεις τον κωδικό στον λογαριασμό σου στο Zino.

Για να το κάνεις, ακολούθησε τον παρακάτω σύνδεσμο:

<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>/forgot/recover/<?php
            echo $requestid;
            ?>?hash=<?php
            echo $hash;
            ?>

Αν ο σύνδεσμός δεν λειτουργεί, δοκίμασε να τον αντιγράψεις στην γραμμή διευθύνσεων.

Αν δεν έχεις ζητήσει να αλλάξεις τον κωδικό σου, μπορείς να αγνοήσεις αυτό το μήνυμα.<?php
            Element( 'email/footer', false );
        }
    }
?>
