<?php
	
	function ElementJournalView( tInteger $id , tInteger $commentid , tInteger $offset ) {
		global $page;
		global $rabbit_settings;
		global $user;
		global $libs;
		
		$libs->Load( 'comment' );
		$libs->Load( 'favourite' );
		$journal = New Journal( $id->Get() );
		$commentid = $commentid->Get();
		$offset = $offset->Get();
		$finder = New FavouriteFinder();
		$fav = $finder->FindByUserAndEntity( $user, $journal );
		
		if ( $journal->Exists() ) {
			if ( $offset <= 0 ) {
				$offset = 1;
			}
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
							?><dd class="addfav"><a href="" class="<?php
							if ( !$fav ) {
								?>add<?php
							}
							else {
								?>isadded<?php
							}
							?>" title="<?php
							if ( !$fav ) {
								?>Προσθήκη στα αγαπημένα<?php
							}
							else {
								?>Αγαπημένο<?php
							}
							?>" onclick="JournalView.AddFav( '<?php
							echo $journal->Id;
							?>' , this );return false;"><?php
							if ( !$fav ) {
								?>Προσθήκη στα αγαπημένα<?php
							}
							?></a></dd><?php
						}
						?></dl><?php
						if ( $journal->User->Id == $user->Id || $user->HasPermission( PERMISSION_JOURNAL_DELETE_ALL ) ) {
							?><div class="owner">
								<div class="edit">
									<a href="?p=addjournal&amp;id=<?php
									echo $journal->Id;
									?>">Επεξεργασία</a>
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
					echo $journal->Text; //purposely not formatted
					?></p>
				</div><?php
				?><div class="comments"><?php
				if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
					Element( 'comment/reply', $journal->Id, TYPE_JOURNAL );
				}
				if ( $journal->Numcomments > 0 ) {
					$finder = New CommentFinder();
					if ( $commentid == 0 ) {
						$comments = $finder->FindByPage( $journal , $offset , true );
					}
					else {
						$speccomment = New Comment( $commentid );
						$comments = $finder->FindNear( $journal , $speccomment );
						$offset = $comments[ 0 ];
						$comments = $comments[ 1 ];
					}
					Element( 'comment/list' , $comments , 0 , 0 );
					?><div class="pagifycomments"><?php
						Element( 'pagify' , $offset , 'journal&id=' . $journal->Id , $journal->Numcomments , 50 , 'offset' );
					?></div><?php
				}
				?></div><?php
			}
			else {
				$page->SetTitle( "Η καταχώρηση έχει διαγραφεί" );
				?>Η καταχώρηση έχει διαγραφεί<?php
			}
			?></div><?php
		}
		else {
			$page->SetTitle( "Η καταχώρηση δεν υπάρχει" );
			?>Η καταχώρηση δεν υπάρχει<?php
		}
		?><div class="eof"></div><?php
	}
?>
