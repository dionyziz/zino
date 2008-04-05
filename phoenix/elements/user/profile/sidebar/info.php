<?php
	function ElementUserProfileSidebarInfo( $theuser ) {
		$hair = array( 
			'black' => 'Μαύρο',
			'brown' => 'Καστανό',
			'red' => 'Κόκκινο',
			'blond' => 'Ξανθό',
			'highlights' => 'Ανταύγειες',
			'dark' => 'Σκούρο καφέ',
			'grey' => 'Γκρι', 
			'skinhead' => 'Skinhead'
		);
		$eyes = array( 
			'black' => 'Μαύρο',
			'brown' => 'Καφέ',
			'green' => 'Πράσινο',
			'blue'	=> 'Μπλε',
			'gray'	=> 'Γκρι'
		);
		?><div class="info">
			<dl><?php
				if ( $theuser->Profile->Age ) {
					?><dt><strong>Ηλικία</strong></dt>
					<dd><?php
					echo $theuser->Age;
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
				if ( $theuser->Profile->Haircolor != '' ) {
					?><dt><strong>Χρώμα μαλλιών</strong></dt>
					<dd><?php
					echo $hair[ $theuser->Profile->Haircolor ];
					?></dd><?php
				}
				if ( $theuser->Profile->Eyecolor != '' ) {
					?><dt><strong>Χρώμα ματιών</strong></dt>
					<dd><?php
					echo $eyes[ $theuser->Profile->Eyecolor ];
					?></dd><?php
				}
			?></dl>
		</div><?php
	}
?>