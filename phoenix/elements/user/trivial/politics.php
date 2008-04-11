<?php
	
	function ElementUserTrivialPolitics( $politic , $gender ) {
		if ( $gender == 'm' || $gender == '-' ) {				
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
		echo htmlspecialchars( $politics[ $politic ] );
	}
?>
