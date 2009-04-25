<?php
    class ElementAdManagerBank {
        public function Render() { 
            ?><div class="buyad">
            <h2 class="ad">Διαφήμιση στο Zino</h2>
            <div class="create status">
                <div class="left">
                    <img src="http://static.zino.gr/phoenix/alert-note.png" alt="Όλα καλά!" title="Όλα καλά!" />
                </div>
                <div class="right">
                    <h3>Σας ευχαριστούμε!</h3>
                    <p><strong>Για να ολοκληρωθεί η συναλλαγή σας, παρακαλούμε πραγματοποιήστε την 
                    τραπεζική κατάθεση:</strong><p/>
                    <ul class="bankaccount">
                        <li><label>Τράπεζα:</label> Ταχυδρομικό ταμιευτήριο</li>
                        <li><label>Αριθμός λογαριασμού:</label> 00088766033-9 01</li>
                        <li><label>Αιτιολογία:</label> <?php
                        echo htmlspecialchars( $user->Profile->Lastname );
                        ?> <?php
                        echo htmlspecialchars( $user->Profile->Firstname );
                        ?></li>
                    </ul>
                    <p><strong>Αποστείλτε μας την απόδειξη κατάθεσης μέσω e-mail στο 
                    <a href="mailto:ads@zino.gr">ads@zino.gr</a>. Αν επιθυμείτε έκδοση τιμολογίου,
                    συμπεριλάβετε την επωνυμία της εταιρίας σας καθώς και το ΑΦΜ σας.</strong></p>
                    <p>Ελπίζουμε η συνεργασία μαζί μας να είναι άριστη.<br />Σε περίπτωση οποιουδήποτε 
                    προβλήματος ή ερώτησης, μην διστάσετε να επικοινωνήσετε μαζί μας στο 
                    <a href="mailto:ads@zino.gr">ads@zino.gr</a></p>
                    <h3>Επόμενα βήματα</h3>
                    <p>Η διαφήμισή σας στο Zino θα ενεργοποιηθεί αυτόματα μόλις επιβεβαιωθεί η πληρωμή 
                    σας.<br />Μπορείτε να παρακολουθείτε την πορεία της διαφήμισής σας από την <a href="?p=admanager/list">σελίδα 
                    διαχείρησης διαφημίσεων</a>.</p>
                </div>
            </div>
            </div><?php
        }
    }
?>
