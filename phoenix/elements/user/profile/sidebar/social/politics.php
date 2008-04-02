<?php
	function ElementUserProfileSidebarSocialPolitics( $theuser ) {
		if ( $theuser->Profile->Politics != '' ) {
			if ( $theuser->Gender == 'm' || $theuser->Gender == '-' ) {
				$politics = array( 
					'right' => 'Δεξιός',
					'left' => 'Αριστερός',
					'center' => 'Κεντρώος',
					'radical left' => 'Ακροαριστερός',
					'radical right' => 'Ακροδεξιός',
					'nothing' => 'Τίποτα'
				);
			}
			else {
				$politics = array( 
					'right' => 'Δεξιά',
					'left' => 'Αριστερή',
					'center' => 'Κεντρώα',
					'radical left' => 'Ακροαριστερή',
					'radical right' => 'Ακροδεξιά',
					'nothing' => 'Τίποτα'
				);
			}
			?><dt><strong>Πολιτική ιδεολογία</strong></dt>
			<dd><?php
			echo $politics[ $theuser->Profile->Politics ];
			?></dd><?php
		}
	}
?>