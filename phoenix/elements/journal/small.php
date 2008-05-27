<?php
	
	function ElementJournalSmall( $journal ) {
		?><div class="journalsmall">
			<h4><a href="?p=journal&amp;id=<?php
			echo $journal->Id;
			?>"><?php
			echo htmlspecialchars( $journal->Title );
			?></a></h4>
			<p><?php
			echo $journal->GetText( 300 );
			?></p>
			<ul>
				<li>
					<dl>
						<dt class="addfav"><a href="" onclick="JournalList.AddFav( this );return false;" title="Προσθήκη στα αγαπημένα"></a></dt>
					</dl>
				</li>
				<li>
					<dl><?php
					if ( $journal->Numcomments > 0 ) {
						?><dt class="commentsnum"><a href="?p=poll&amp;id=<?php
						echo $journal->Id;
						?>"><?php
						echo $journal->Numcomments;
						?> σχόλι<?php
						if ( $journal->Numcomments == 1 ) {
							?>ο<?php
						}
						else {
							?>α<?php
						}
						?></a></dt><?php
					}
					?></dl>
				</li>
			</ul>
		</div><?php
	}
?>
