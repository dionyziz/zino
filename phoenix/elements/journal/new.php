<?php
	
	function ElementJournalNew( tInteger $id ) {
		global $user;
		global $page;
		global $water;
		
		$id = $id->Get();
		
		if ( $id != 0 ) {
			$journal = New Journal( $id );
			$page->SetTitle( $journal->Title );
		}
		else {
			$page->SetTitle( "Δημιουργία καταχώρησης" );
		}
		
		Element( 'user/sections' , 'journal' , $user );
		?><div id="journalnew"><?php
			if ( ( isset( $journal ) && $journal->User->Id == $user->Id ) || $id == 0 ) {
				?><form method="post" action="do/journal/new">
					<input type="hidden" name="id" value="<?php
					echo $id;
					?>" />
					<div class="title">
						<span>Τίτλος:</span><input type="text" value="<?php
						if ( $id != 0 ) {
							echo htmlspecialchars( $journal->Title );
						}
						?>" name="title" tabindex="1" />
					</div>
                    <div class="wysiwyg" id="wysiwyg"><?php
                    if ( $id > 0 ) {
                        echo $journal->Text; // purposely no escape here (XSS-safe because of sanitizer)
                    }
                    ?></div>
					<div class="submit">
						<input type="submit" value="Δημοσίευση" onclick="JournalView.Create( '<?php
						echo $id;
						?>' );return false;" />
					</div>
				</form><?php
			}
			else {
				?>Δεν έχεις δικαίωμα να επεξεργαστείς την καταχώρηση<?php
			}
		?></div>
		<div class="eof"></div><?php
	}
?>
