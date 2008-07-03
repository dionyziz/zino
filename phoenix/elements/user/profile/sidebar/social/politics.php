<?php
	function ElementUserProfileSidebarSocialPolitics( $theuser ) {
		if ( $theuser->Profile->Politics != '-' ) {
            /*
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
            */
			?><dt><strong>Πολιτική ιδεολογία</strong></dt>
			<dd><?php
            Element( 'user/trivial/politics', $theuser->Profile->Politics, $theuser->Gender );
			?></dd><?php
		}
	}
?>
