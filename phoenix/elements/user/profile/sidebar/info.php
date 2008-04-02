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
			<dl>
				<dt><strong>Ηλικία</strong></dt>
				<dd>19</dd>
				<dt><strong>Περιοχή</strong></dt>
				<dd>Αθήνα</dd>
				<dt><strong>Πανεπιστήμιο</strong></dt>
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
					echo $hair[ $theuser->Profile->Eyecolor ];
					?></dd><?php
				}
			?></dl>
		</div><?php
	}
?>