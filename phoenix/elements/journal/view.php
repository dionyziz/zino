<?php
	
	function ElementJournalView( tInteger $id ) {
		global $page;
		global $rabbit_settings;
		
		$journal = New Journal( $id->Get() );
		Element( 'user/sections' , 'journal' , $journal->User );
		
		$page->SetTitle( $journal->Title );
		?><div id="journalview">
			<h2><?php
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
					?><dd class="addfav"><a href="">Προσθήκη στα αγαπημένα</a></dd>
					<dd class="edit">
						<a href="" onclick="return false;"><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>pencil.png" alt="Επεξεργασία" title="Επεξεργασία" />Επεξεργασία
					</dd>
					<dd class="delete">
						<a href="" onclick="return false;"><img src="<?php
						echo $rabbit_settings[ 'imagesurl' ];
						?>delete.png" alt="Διαγραφή" title="Διαγραφή" />Διαγραφή
						</a>
					</dd>
					<dd class="lastentries"><a href="?p=journals&amp;username=<?php
					echo $journal->User->Subdomain;
					?>">Παλαιότερες καταχωρήσεις&raquo;</a></dd>
				</dl>
				<div class="eof"></div>
				<p><?php
				echo $journal->Text; //has to get through some editing for tags that are not allowed
				?></p>
			</div>
			<div class="comments"><?php
				Element( 'comment/list' );
			?></div>
			<div class="eof"></div>		
		</div><?php
	}
?>
