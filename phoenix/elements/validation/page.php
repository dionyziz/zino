<?php
    class ElementValidationPage extends Element {
        public function Render( tInteger $userid, tBoolean $firsttime) {
            global $user;
			$userid = $userid->Get();
            $firsttime = $firsttime->Get();
            ?><div class="error">
				<h2>Καλώς ήρθες στο Zino! Έλεγξε τα e-mail σου</h2>
				<p>Ο λογαριασμός σου είναι σχεδόν έτοιμος. Ένας σύνδεσμος για ενεργοποίηση έχει 
                   σταλεί στην διεύθυνση e-mail που δήλωσες μαζί με οδηγίες για να ενεργοποιήσεις 
                   τον λογαριασμό σου.<?php
                if ( !$firsttime ) {
                    ?>
                    <br /><br />
                    Αν δεν έλαβες κάποιο μήνυμα, έλεγξε τον φάκελο "junk" ή "ανεπιθύμητη 
                    αλληλογραφία", ή ζήτησέ μας να σου το ξαναστείλουμε.
                    <form action="do/user/revalidate" method="post">
					<input type="submit" value="Ξαναστείλτε μου το e-mail" />
					<input name="userid" type="hidden" value="<?php
						echo $userid;
					?>" />
                    </form><?php
                }
                ?></p>
			</div><?php
        }

    }
?>
