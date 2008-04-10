<?php
	function ElementUserHaircolor( $color ) {
		$hairs = array( '-'		=> '-',
						'black' => 'Μαύρα', 
						'brown' => 'Καστανά',
						'red' 	=> 'Κόκκινα',
						'blond' => 'Ξανθά',
						'highlights' => 'Ανταύγιες',
						'grey'		=> 'Γκρίζα',
						'skinhead'	=> 'Φαλακρός'
		);
		echo htmlspecialchars( $hairs[ $color ] );
	}
?>