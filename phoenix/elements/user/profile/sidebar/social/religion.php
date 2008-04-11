<?php	
	function ElementUserProfileSidebarSocialReligion( $theuser ) {
		if ( $theuser->Profile->Religion != '-' ) {
			if ( $theuser->Gender == 'm' || $theuser->Gender == '-' ) {
				$religions = array( 
					'christian' => 'Χριστιανός',
					'muslim' => 'Ισλαμιστής',
					'atheist' => 'Άθεος',
					'agnostic' => 'Αγνωστικιστής',
					'nothing' => 'Καμία'
				);
			}
			else {
				$religions = array( 
					'christian' => 'Χριστιανή',
					'muslim' => 'Ισλαμίστρια',
					'atheist' => 'Άθεη',
					'agnostic' => 'Αγνωστικιστής',
					'nothing' => 'Καμία'
				);
			}
			?><dt><strong>Θρήσκευμα</strong></dt>
			<dd><?php
			echo $religions[ $theuser->Profile->Religion ];
			?></dd><?php
		}
	}
?>