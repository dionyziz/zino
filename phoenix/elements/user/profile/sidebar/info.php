<?php
	function ElementUserProfileSidebarInfo( $theuser ) {
		?><div class="info">
			<dl><?php
				if ( $theuser->Profile->Age ) {
					?><dt><strong>Ηλικία</strong></dt>
					<dd><?php
					echo $theuser->Profile->Age;
					?></dd><?php
				}
				if ( $theuser->Profile->Location->Name != '' ) {
					?><dt><strong>Περιοχή</strong></dt>
					<dd><?php
					echo $theuser->Profile->Location->Name;
					?></dd><?php
				}
				?><dt><strong>Πανεπιστήμιο</strong></dt>
				<dd>Ηλεκτρολόγων Μηχ/κων και Μηχ/κων Υπολογιστών - Αθήνα</dd><?php
				if ( $theuser->Profile->Haircolor != '-' ) {
					?><dt><strong>Χρώμα μαλλιών</strong></dt>
					<dd><?php
					Element( 'user/haircolor' , $theuser->Profile->Haircolor );
					?></dd><?php
				}
				if ( $theuser->Profile->Eyecolor != '-' ) {
					?><dt><strong>Χρώμα ματιών</strong></dt>
					<dd><?php
					Element( 'user/eyecolor' , $theuser->Profile->Eyecolor );
					?></dd><?php
				}
			?></dl>
		</div><?php
	}
?>