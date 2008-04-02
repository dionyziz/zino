<?php
	function ElementUserProfileSidebarSocialDrinker( $theuser ) {
		$drinker = array( 
			'yes' => 'Ναι',
			'no' => 'Όχι',
			'socially' => 'Με παρέα'
		);
		?><dt><strong>Πίνεις;</strong></dt>
		<dd><?php
		echo $drinker[ $theuser->Profile->Drinker ];
		?></dd><?php
	}
?>