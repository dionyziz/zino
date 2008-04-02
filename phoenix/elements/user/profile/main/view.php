<?php
	function ElementUserProfileMainView( $theuser ) {
		?><div class="main">
			<div class="photos"><?php
				Element( 'user/profile/main/photos' , $theuser );
			?></div>
			<div class="friends">
				<h3>Οι φίλοι μου</h3><?php
				Element( 'user/list' );
				?><a href="" class="button">Περισσότεροι φίλοι&raquo;</a>
			</div>
			<div class="lastpoll">
				<h3>Δημοσκοπήσεις</h3><?php
				Element( 'poll/small' , true );
				?><a href="" class="button">Περισσότερες δημοσκοπήσεις&raquo;</a>
			</div>
			<div class="questions">
				<h3>Ερωτήσεις</h3><?php
				Element( 'user/profile/main/questions' , $theuser );
				?><a href="" class="button">Περισσότερες ερωτήσεις&raquo;</a>
			</div>
			<div style="clear:right"></div>
			<div class="lastjournal">
				<h3>Ημερολόγιο</h3><?php
				Element( 'journal/small' , $theuser );
				?><a href="" class="button">Περισσότερες καταχωρήσεις&raquo;</a>
			</div>
			<div class="comments">
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
		</div>
		<?php
	
	
	}
?>