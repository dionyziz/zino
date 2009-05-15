<?php
    // Content-type: text/plain

    class ElementUserEmailWelcome extends Element {
        public function Render( User $target, $link ) {
            global $rabbit_settings;

            ?>Γεια σου <?php
            echo $target->Name;
            ?>, 

Καλώς ήρθες στο Zino! Πρέπει τώρα να ενεργοποιήσεις τον λογαριασμό σου. Κάνε κλικ στον παρακάτω 
σύνδεσμο ή αντέγραψέ τον στην γραμμή διευθύνσεων:

<?php
        echo $link;
    ?>

Αν δεν έχεις δημιουργήσει λογαριασμό στο Zino, δε χρειάζεται να κάνεις τίποτα. Δεν θα λάβεις άλλα 
μηνύματα από εμάς και ο λογαριασμός θα απενεργοποιηθεί αυτόματα.
    <?php    
        Element( 'email/footer', false );

        return 'Zino - Καλώς ήρθες';
        }
    }
?>
