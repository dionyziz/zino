<?php
    class ElementAdManagerFailure extends Element {
        public function Render() {
            ?><div class="buyad">
            <h2 class="ad">Διαφήμιση στο Zino</h2>
            <div class="create status">
                <div class="left">
                    <img src="http://static.zino.gr/phoenix/alert-stop2.png" alt="Έχουμε πρόβλημα" title="Έχουμε πρόβλημα" />
                </div>
                <div class="right">
                    <h3>Η πληρωμή δεν έχει ολοκληρωθεί!</h3>
                    <p>Δεν έχετε ολοκληρώσει την πληρωμή σας.<br />Σε περίπτωση οποιουδήποτε 
                    προβλήματος ή ερώτησης, παρακαλούμε επικοινωνήσετε μαζί μας στο 
                    <a href="mailto:ads@zino.gr">ads@zino.gr</a></p>
                    <p>Μπορείτε να ολοκληρώσετε την πληρωμή σας αργότερα από την <a href="?p=admanager/list">σελίδα 
                    διαχείρησης διαφημίσεων</a>.<br /><strong>Η διαφήμισή σας δεν θα ενεργοποιηθεί μέχρι τότε.</strong></p>
                </div>
            </div>
            </div><?php
        }
    }
?>
