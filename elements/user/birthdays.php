<?php
	function ElementUserBirthdays() {
		$birthdays = getTodayBirthdays();
		if ( !empty( $birthdays ) ) { // If there are users that have bday today
			if ( count ( $birthdays ) == 1) {
				?>Σήμερα έχει γενέθλια: <?php
			}
			else {
				?>Σήμερα έχουν γενέθλια: <?php
			}
			foreach ( $birthdays as $bduser ) {
				Element( 'user/static', $bduser );
				?>&nbsp;&nbsp;<?php
			}
		}
	}
?>