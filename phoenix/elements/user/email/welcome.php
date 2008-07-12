<?php
    // Content-type: text/plain

    class ElementUserEmailWelcome extends Element {
        public function Render( User $target ) {
            global $rabbit_settings;

            ?>Γεια σου <?php
            echo $target->Name;
            ?>, 

    Πρόσφατα δημιούργησες ένα λογαριασμό στο Zino χρησιμοποιώντας αυτή την ηλεκτρονική διεύθυνση.

    Μπορείς να προσθέσεις πληροφορίες για τον εαυτό σου στο προφίλ σου ακολουθώντας τον παρακάτω σύνδεσμο:
    <?php
    echo $rabbit_settings[ 'webaddress' ];
    ?>/settings

    (Αν δεν μπορείς να κάνεις κλικ στον σύνδεσμο, δοκίμασε να τον αντιγράψεις και να τον επικολλήσεις στην θέση διεύθυνσης.)

    Αν δεν δημιούργησες λογαριασμό στο Zino, παρακαλούμε αγνόησε αυτό το μήνυμα.
    Μπορείς να επικοινωνήσεις με το info@zino.gr για οποιαδήποτε ερώτηση.
            <?php    

            Element( 'email/footer', false );

            return 'Zino - Καλώς ήρθες';
        }
    }
?>
