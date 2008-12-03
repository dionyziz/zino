<?php
    /// Content-type: text/plain ///
    class ElementEmailValidate extends Element {
        public function Render( $username, $link ) {
            w_assert( !empty( $username ) );
            w_assert( !empty( $link ) );
            
            ?>Κάνε κλικ στον παρακάτω σύνδεσμο για να επιβεβαιώσεις το e-mail σου:

<?php
            echo $link;
?>

Αν δεν έχεις δημιουργήσει λογαριασμό στο Zino, παρακαλούμε να αγνοήσεις αυτό το e-mail.

Αυτό το e-mail είναι ο μόνος τρόπος επιβεβαίωσης του λογαριασμού σου στο Zino. Για την δική σου 
ασφάλεια, να θυμάσαι ότι κάποιο μέλος της Ομάδας Ανάπτυξης του Zino δεν πρόκειται να σου ζητήσει 
ποτέ τον κωδικό πρόσβασής σου σε καμία απολύτως περίπτωση!

<?php
            Element( 'email/footer' );
            
            return $username . ', παρακαλούμε επιβεβαίωσε το e-mail σου στο Zino';
        }
    }
?>
