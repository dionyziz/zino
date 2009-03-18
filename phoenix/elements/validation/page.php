<?php
    class ElementValidationPage extends Element {
        public function Render() {
            global $user;
			$link =  $rabbit_settings[ 'webaddress' ] . '/?p=revalidate'
            ?><p>Ο λογαριασμός σου δεν έχει ενεργοποιηθεί ακόμη. Θα πρέπει να χρησιμοποιήσεις τον σύνδεσμο στο e-mail σου για να τον ενεργοποιήσεις. Δεν έλαβες κάποιο e-mail? Έλεγξε τον φάκελο junk ή <a href="<?php
				echo $link;
			?>">ζήτησέ μας να σου το ξαναστείλουμε</a></p><?php
        }

    }
?>
