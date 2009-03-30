<?php
    class ElementMailSent extends Element {
        public function Render( tBoolean $mailsent ) {
            // Get Parameter
            $mailsent = $mailsent->Get();
            
            if ( $mailsent == true ) {
                ?><p>Το μήνυμα στάλθηκε επιτυχώς.</p><?php
            }
            else {
                ?><p>Παρουσιάστικε πρόβλημα στην αποστολή του μηνύματος. Παρακαλώ, προσπαθίστε ξανά αργότερα.</p><?php
            }
        }
    }
?>
