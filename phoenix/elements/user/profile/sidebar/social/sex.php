<?php
	function ElementUserProfileSidebarSocialSex( $theuser ) {
		if ( $theuser->Profile->Sexualorientation != '' ) {
			if ( $theuser->Gender == 'm' || $theuser->Gender == '-' ) {
				$sex = array( 
					'straight' => 'Straight',
					'bi' => 'Bisexual',
					'gay' => 'Gay'
				);
			}
			else {
				$sex = array( 
					'straight' => 'Straight',
					'bi' => 'Bisexual',
					'gay' => 'Λεσβία'
				);
			}
			?><dt><strong>Σεξουαλικές προτιμήσεις</strong></dt>
			<dd><?php
			echo $sex[ $theuser->Profile->Sexualorientation ];
			?></dd><?php
		}
	}
?>
