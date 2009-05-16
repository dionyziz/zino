<?php
    class Element404 extends Element {
        public function Render( $error = false ) {
            header( 'HTTP/1.0 404 Not Found' );
            ?>Συγγνώμη, αλλά η σελίδα που ζητήσατε δεν είναι διαθέσιμη.<br />
            Προσπαθήστε ξανά σε μερικά λεπτά.<br /><?php
        }
    }
?>
