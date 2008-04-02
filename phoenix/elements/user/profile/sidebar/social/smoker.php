<?php
	function ElementUserProfileSidebarSocialSmoker( $theuser ) {
		$smoker = array( 
			'yes' => 'Ναι',
			'no' => 'Όχι',
			'socially' => 'Με παρέα'
		);
		?><dt><strong>Καπνίζεις;</strong></dt>
		<dd><?php
		echo $smoker[ $theuser->Profile->Smoker ];
		?></dd><?php
	}
?>