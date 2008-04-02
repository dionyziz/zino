<?php
	function ElementUserProfileSidebarContacts( $theuser ) {
		?><dl><?php
			if ( $theuser->Profile->Skype != '' ) {
				?><dt><img src="http://static.zino.gr/phoenix/skype.jpg" alt="skype" title="skype" /></dt>
				<dd><?php 
				echo htmlspecialchars( $theuser->Profile->Skype );
				?></dd><?php
			}
			if ( $theuser->Profile->Msn != '' ) {
				?><dt><img src="http://static.zino.gr/phoenix/msn.jpg" alt="msn" title="msn" /></dt>
				<dd><?php
				echo htmlspecialchars( $theuser->Profile->Msn );
				?></dd><?php
			}
			if ( $theuser->Profile->Gtalk != '' ) {
				?><dt><img src="http://static.zino.gr/phoenix/gtalk.jpg" alt="msn" title="msn" /></dt>
				<dd><?php
				echo htmlspecialchars( $theuser->Profile->Gtalk );
				?></dd><?php
			}
			if ( $theuser->Profile->Yim != '' ) {
				?><dt><img src="http://static.zino.gr/phoenix/yahoo.jpg" alt="yahoo" title="yahoo" /></dt>
				<dd><?php
				echo htmlspecialchars( $theuser->Profile->Yim );
				?></dd><?php
			}
		?></dl><?php
	}
?>
