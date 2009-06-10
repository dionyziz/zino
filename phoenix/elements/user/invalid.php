<?php
    class ElementUserInvalid extends Element {
        public function Render() {
            global $user;

            if ( $user->Exists() ) {
                return Redirect();
            }

            ?>
            <h3>Ο κωδικός ή το ψευδώνυμο δεν είναι σωστά <span>.</span></h3>
            <ul style="margin:0; padding:0 0 10pt 14pt; list-style: disc;">
                <li>
                    Τα κεφαλαία και τα μικρά γράμματα παίζουν ρόλο.<br />
                    Κοίτα μήπως το πλήκτρο για τα κεφαλαία γράμματα είναι ενεργοποιημένο.
                </li>
                <li>
                    Βεβαιώσου ότι γράφεις τον κωδικό στα Αγγλικά ή στα Ελληνικά όπως τον
                    είχες γράφει αρχικά.
                </li>
                <li>
                    Έχεις ξεχάσει τον κωδικό σου; <a href="forgot">Επανέφερέ τον</a>.
                </li>
            </ul>
            <?php
        }
    }
?>
