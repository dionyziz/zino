<?php

	function ElementUserAvatarFile( $theuser ) {
		if ( $theuser->Position() <= 0 ) {
		}
		else if ( $theuser->Position() < 50 ) {
			?>egg<?php
		}
		else if ( $theuser->Position() < 100 ) {
			?>cracked<?php
		}
		else if ( $theuser->Position() < 150 ) {
			?>dragonbaby<?php
		}
		else if ( $theuser->Position() < 200 ) {
			?>adultdragon<?php
		}
		else if ( $theuser->Position() < 250 ) {
			?>icedragon<?php
		}
		else if ( $theuser->Position() < 300 ) {
			?>sundragon<?php
		}
		else if ( $theuser->Position() < 350 ) {
			?>mountaindragon<?php
		}
		else if ( $theuser->Position() < 400 ) {
			?>firedragon<?php
		}
		else if ( $theuser->Position() < 450 ) {
			?>darknessdragon<?php
		}
		else {
			?>lightdragon<?php
		}
	}

?>
