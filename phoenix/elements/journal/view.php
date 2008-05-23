<?php
	
	function ElementJournalView( tInteger $id , tInteger $commentid ) {
		global $page;
		global $rabbit_settings;
		global $user;
		global $libs;
		
		$libs->Load( 'comment' );
		$journal = New Journal( $id->Get() );
		$commentid = $commentid->Get();
		Element( 'user/sections' , 'journal' , $journal->User );
		
		?><div id="journalview"><?php
			if ( !$journal->IsDeleted() ) {
				$page->SetTitle( $journal->Title );
				?><h2><?php
				echo htmlspecialchars( $journal->Title );
				?></h2>
				<div class="journal" style="clear:none;">	
					<dl><?php
						if ( $journal->Numcomments > 0 ) {
							?><dd class="commentsnum"><?php
							echo $journal->Numcomments;
							?> σχόλι<?php
							if ( $journal->Numcomments == 1 ) {
								?>ο<?php
							}
							else {
								?>α<?php
							}
							?></dd><?php
						}
						if ( $journal->User->Id != $user->Id ) {
							?><dd class="addfav"><a href="">Προσθήκη στα αγαπημένα</a></dd><?php
						}

						?></dl><?php
						if ( $journal->User->Id == $user->Id ) {
							?><div class="owner">
								<div class="edit">
									<a href="" onclick="return false;">Επεξεργασία</a>
								</div>
								<div class="delete">
									<a href="" onclick="JournalView.Delete( '<?php
									echo $journal->Id;
									?>' );return false;">Διαγραφή
									</a>
								</div>						
							</div><?php
						}
					?><div class="eof"></div>
					<p><?php
					echo $journal->Text; //has to get through some editing for tags that are not allowed
					?></p>
				</div>
				<div class="comments"><?php
					Element( 'comment/list' );
				?></div><?php
			}
			else {
				$page->SetTitle( "Η καταχώρηση δεν υπάρχει" );
				?>Η καταχώρηση δεν υπάρχει<?php
			}
			if ( $journal->Numcomments > 0 ) {
				$finder = New CommentFinder();
				$comments = $finder->FindByPage( $journal , 0 , true );
				?><div class="comments"><?php
					Element( 'comment/list' , $comments , 0 , 0 );
				?></div><?php
			}
		?><div class="eof"></div>
		</div><?php
	}
?>
