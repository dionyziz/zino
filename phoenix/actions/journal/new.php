<?php
	function ActionJournalNew( tInteger $id , tString $title , tString $text ) {
		global $user;
		
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
			$journal = New Journal();
		}
		$journal->Title = $title;
		$journal->Text = $text;
		$journal->Save();
		
		return Redirect( '?p=journal&id=' . $journal->Id );
	}
?>
