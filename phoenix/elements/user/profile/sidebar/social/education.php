<?php
	function ElementUserProfileSidebarSocialEducation( $theuser ) {
		if ( $theuser->Profile->Education != '' ) {
			$education = array( 
					'elementary' => 'Δημοτικό',
					'gymnasium' => 'Γυμνάσιο',
					'TEE' => 'ΤΕΕ',
					'lyceum' => 'Λύκειο',
					'ΤΕΙ' => 'TEI',
					'university' => 'Πανεπιστήμιο'
			);
			?><dt><strong>Μόρφωση</strong></dt>
			<dd><?php
			echo $education[ $theuser->Profile->Education ];
			?></dd><?php
		}
	}
?>
