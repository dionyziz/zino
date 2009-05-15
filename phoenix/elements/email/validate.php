<?php
    /// Content-type: text/plain ///
    class ElementEmailValidate extends Element {
        public function Render( $username, $link ) {
            w_assert( !empty( $username ) );
            w_assert( !empty( $link ) );
            
            ?>Καλώς ήρθες στο Zino! Πρέπει τώρα να ενεργοποιήσεις τον λογαριασμό σου. Κάνε κλικ στον 
παρακάτω σύνδεσμο ή αντέγραψέ τον στην γραμμή διευθύνσεων:

<?php
            echo $link;
?>

Αν δεν έχεις δημιουργήσει λογαριασμό στο Zino, δε χρειάζεται να κάνεις τίποτα. Δεν θα λάβεις άλλα 
μηνύματα από εμάς και ο λογαριασμός θα απενεργοποιηθεί αυτόματα.

<?php
            Element( 'email/footer' );
            
            return $username . ', παρακαλούμε επιβεβαίωσε το e-mail σου στο Zino';
        }
    }
?>
