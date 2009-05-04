<?php
    class ElementValidationPage extends Element {
        public function Render( tInteger $userid, tBoolean $firsttime) {
            global $user;
			$userid = $userid->Get();
            $firsttime = $firsttime->Get();
            ?><div class="error">
				<h2>Μη επιβεβαιωμένο email</h2>
				<p>Ο λογαριασμός σου δεν έχει ενεργοποιηθεί ακόμη. Θα πρέπει να χρησιμοποιήσεις τον σύνδεσμο στο e-mail σου για να τον ενεργοποιήσεις. Δεν έλαβες κάποιο e-mail? Έλεγξε τον φάκελο junk ή ζήτησέ μας να σου το ξαναστείλουμε.<?php
                if ( !$firsttime ) {
                    ?><form action="do/user/revalidate" method="post">
					<input type="submit" value="Αποστολή email" />
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
