<?php
	function ElementPmShowfolder( $folder ) {
		global $water;
		//this element will show all contents, aka messages for one specific folder (inbox and outbox included for now)
		//folder is an instanciated class in case of a folder or an id with -1 for inbox and -2 for outbox
		
		if ( is_int( $folder ) ) {
			if ( $folder == -1 ) {
				$inbox = new PMInbox();
				$messages = $inbox->Messages();
			}
			else if ( $folder == -2 ) {
				$outbox = new PMOutbox();
				$messages = $outbox->Messages();
			}
		}
		else {
			$messages = $folder->Messages();
		}
		$pmsinfolder = count( $messages );
		
		if ( $pmsinfolder == 0 ) {
			if ( $folder == -1 ) {
				?>Δεν έχεις εισερχόμενα μηνύματα.<?php
			}
			else if ( $folder == -2 ) {
				?>Δεν έχεις στείλει ακόμα κάποιο μήνυμα.<?php
			}
			else {
				?>Δεν έχεις μηνύματα σε αυτό το φάκελο.<br />
				Μετακίνησε κάποια μηνύματα με το ποντίκι σε αυτό το φάκελο για να τα μεταφέρεις εδώ.<?php
			}
		}
		else {
			foreach ( $messages as $msg ) {
				Element( 'pm/onepm' , $msg , $folder );
			}
		}
	}
?>
