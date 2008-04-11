<?php
	function ElementUserYesno( $answer ) {
		$yesno = array( '-'		=> '-',
						'yes' => 'Ναι', 
						'no' => 'Όχι',
						'socially' => 'Με παρέα'
		);
		echo htmlspecialchars( $yesno[ $answer ] );
	}
?>
