<?php

	function ElementUserProfileCCRelated( $theuser ) {
		
		static $prizes = array(
			'448' => array( 'Πιο ενεργός χρήστης 2006', 'Καλύτερη υπογραφή 2006' ),
			'452' => array( 'Καλύτερο προφίλ 2006' ),
			'699' => array( 'Καλύτερος δημοσιογράφος 2006', 'Καλύτερο άρθρο 2006' ),
			'719' => array( 'Χειρότερο άρθρο 2006' )
		);
		
		?><div class="ccrelated">
			<h4>σχετικά με chit-chat</h4>
			<ul>
				<li><dl>
					<dt>ρόλος</dt>
					<dd><?php
						echo mystrtolower( $theuser->Rank() );
					?></dd>
				</dl></li>
				<li><dl class="l">
					<dt>κατάταξη</dt>
					<dd><?php
						strtolower( Element( 'user/avatar/title', $theuser ) );
					?></dd>
				</dl></li>
				<li><dl>
					<dt>μέλος</dt>
					<dd>εδώ και <?php
						echo $theuser->RegisterSince();
					?></dd>
				</dl></li>
				<li><dl class="l">
					<dt><?php
					if ( $theuser->Gender() == "-" || $theuser->Gender() == "male" ) { 
						?>συνδεδεμένος <?php
					}
					else { 
						?>συνδεδεμένη <?php
					} 
					?></dt>
					<dd><?php
					if ( $theuser->IsOnline() ) {
						?>αυτή τη στιγμή! <?php
					}
					else { 
						?>πριν <?php
						echo $theuser->ActiveSince(); 
					} 
					?></dd>
				</dl></li><?php
				
				if ( isset( $prizes[ $theuser->Id() ] ) ) {
					?><li><dl>
						<dt>βραβεία</dt>
						<dd><?php
							$i = 1;
							foreach ( $prizes[ $theuser->Id() ] as $prize ) {
								echo $prize;
								if ( count( $prizes[ $theuser->Id() ] ) != $i ) {
									echo ", ";
								}
								$i++;
							}
						?></dd>
					</dl></li><?php
				}
				
			?></ul>
		</div><?php
	}
	
?>