<?php
	function ElementUserYesno( $answer ) {
		$yesno = array( '-'		=> '-',
						'yes' => 'Íáé', 
						'no' => '¼÷é'
		);
		echo htmlspecialchars( $yesno[ $answer ] );
	}
?>
