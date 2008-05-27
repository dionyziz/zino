<?php
	
	function ElementJournalSmall( $journal ) {
		global $user;
		global $libs;
		
		$libs->Load( 'favourite' );
		$finder = New FavouriteFinder();
		$fav = $finder->FindByUserAndEntity( $user, $journal );
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
						<dt class="addfav"><a href="" class="<?php
						if ( !$fav ) {
							?>add<?php
						}
						else {
							?>isadded<?php
						}
						?>" onclick="JournalList.AddFav( '<?php
						echo $journal->Id;
						?>' , this );return false;" title="Προσθήκη στα αγαπημένα"></a></dt>
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
