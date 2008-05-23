<?php
	function ActionJournalNew( tInteger $id , tString $title , tString $text ) {
		global $user;
		
        header( 'Content-type: text/plain' );

		$id = $id->Get();
		$title = $title->Get();
		$text = $text->Get();
		
		if ( $id != 0 ) {
			$journal = New Journal( $id );
			if ( $journal->User->Id != $user->Id ) {
				return;
			}
		}
		else {
            if ( !$user->Exists() ) {
                return;
            }
			$journal = New Journal();
		}
		$journal->Title = $title;
		$journal->Text = $text; // TODO: SECURITY: sanity check
		$journal->Save();
		
		return Redirect( '?p=journal&id=' . $journal->Id );
	}
?>
