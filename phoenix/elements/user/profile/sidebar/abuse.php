<?php
    class ElementUserProfileSidebarAbuse extends Element {
        public function Render() {
            ?><a href="" class="report">Αναφορά κακής χρήσης</a>
            <div id="reportabusemodal" style="display:none">
                <h3 class="modaltitle">Αναφορά κακής χρήσης</h3>
                <form>
                    <p>Πρόκειται να αναφέρεις την παραβίαση των όρων χρήσης από αυτό το χρήστη.</p>
                    <p><strong>Η αναφορά αυτή είναι εμπιστευτική.</strong></p>
                    <p>Αν δεν συμφωνείς με τις απόψεις του, δεν είναι λόγος για αναφορά.
					Θα ελέγξουμε την περίπτωση παραβίασης και θα παρέμβουμε μόνο αν πραγματικά υπάρχει.</p>
					
					<div>
						<label for="reportreason">Αιτιολογία:</label>
						<select name="reportreason" id="reportreason">
							<option>Επίλεξε μία</option>
							<option>Ανάρτηση πορνογραφικού υλικού</option>
							<option>Ρατσιστική επίθεση</option>
							<option>Ανάρτηση διαφημιστικού ή επαναλαμβανόμενου περιεχομένου (spam)</option>
							<option>Λογαριασμός που δεν αντιστοιχεί σε πραγματικό πρόσωπο (fake)</option>
							<option>Παρενόχληση ανηλίκου</option>
						</select>
					</div>
					
					<div>
						<label for="reportcomments" style="display:block;margin-bottom:10px;">Σύνδεσμοι προς το περιεχόμενο που παραβιάζει τους όρους:</label>
						<textarea name="reportcomments" id="reportcomments"></textarea>
					</div>
					
                    <div class="buttons">
                        <a href="" class="button">Αποστολή Αναφοράς</a>
                        <a href="" class="button">Ακύρωση</a>
                    </div>
                </form>
            </div><?php
        }
    }
?>
