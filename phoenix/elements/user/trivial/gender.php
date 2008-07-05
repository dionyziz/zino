<?php
	
	function ElementUserTrivialGender( $gender ) {
		$sex = array( '-'	=>	'-',
					  'm' 	=> 'Άνδρας',
					  'f'	=> 'Γυναίκα'
		);
		echo htmlspecialchars( $sex[ $gender ] );
	}
?>
