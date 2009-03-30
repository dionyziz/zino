<?php
    class ElementAdManagerFailure extends Element {
        public function Render() {
            ?><div class="buyad">
            <h2 class="ad">Διαφήμιση στο Zino</h2>
            <div class="create checkout">
                <h3>Η πληρωμή σας δεν έχει ολοκληρωθεί</h3>
                <p>Δεν έχετε ολοκληρώσει την πληρωμή σας. Σε περίπτωση οποιουδήποτε 
                προβλήματος ή ερώτησης, παρακαλούμε επικοινωνήσετε μαζί μας στο 
                <a href="mailto:ads@zino.gr">ads@zino.gr</a></p>
                <p>Μπορείτε να ολοκληρώσετε την πληρωμή σας αργότερα από την <a href="?p=admanager/list">σελίδα 
                διαχείρησης διαφημίσεων</a>. Η διαφήμισή σας δεν θα ενεργοποιηθεί μέχρι τότε.</p>
            </div>
            </div><?php
        }
    }
?>
