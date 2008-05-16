<?php
	function ElementUserProfileMainView( $theuser ) {
		global $libs;
		
		$libs->Load( 'poll/poll' );
		
		$finder = New PollFinder();
		$polls = $finder->FindByUser( $theuser , 0 , 1 );
		$finder = New JournalFinder();
		$journals = $finder->FindByUser( $theuser , 0 , 1 );
		?><div class="main">
			<div class="photos"><?php
				Element( 'user/profile/main/photos' , $theuser );
			?></div>
			<div class="friends">
				<h3>Οι φίλοι μου</h3><?php
				Element( 'user/list' );
				?><a href="" class="button">Περισσότεροι φίλοι&raquo;</a>
			</div><?php
			if ( !empty( $poll ) ) {
				?><div class="lastpoll">
					<h3>Δημοσκοπήσεις</h3><?php
					Element( 'poll/small' , $polls[ 0 ] , true );
					?><a href="?p=polls&amp;username=<?php
					echo $theuser->Subdomain;
					?>" class="button">Περισσότερες δημοσκοπήσεις&raquo;</a>
				</div><?php
			}
			?><div class="questions">
				<h3>Ερωτήσεις</h3><?php
				Element( 'user/profile/main/questions' , $theuser );
				?><a href="" class="button">Περισσότερες ερωτήσεις&raquo;</a>
			</div>
			<div style="clear:right"></div><?php
			if ( !empty( $journal ) ) {
				?><div class="lastjournal">
					<h3>Ημερολόγιο</h3><?php
					Element( 'journal/small' , $journals[ 0 ] );
					?><a href="?p=journals&amp;username=<?php
					echo $theuser->Subdomain;
					?>" class="button">Περισσότερες καταχωρήσεις&raquo;</a>
				</div><?php
			}
			?><div class="comments">
				<h3>Σχόλια στο προφίλ <?php
				if ( $theuser->Gender == 'm' || $user->Gender == '-' ) {
					?>του <?php
				}
				else {
					?>της <?php
				}
				Element( 'user/name' , $theuser , false );
				?></h3><?php
				Element( 'comment/list' );
			?></div>
		</div><?php	
	}
?>
