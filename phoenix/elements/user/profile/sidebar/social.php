<?php
	function ElementUserProfileSidebarSocial( $theuser ) {
		$educations = array( 
				'elementary' => 'δημοτικό',
				'gymnasium' => 'γυμνάσιο',
				'TEE' => 'ΤΕΕ',
				'lyceum' => 'λύκειο',
				'ΤΕΙ' => 'TEI',
				'university' => 'πανεπιστήμιο'
		);
		?><dl>
			<dt><strong>Σεξουαλικές προτιμήσεις</strong></dt>
			<dd>Gay</dd>
			
			<dt><strong>Καπνίζεις;</strong></dt>
			<dd>Όχι</dd>
			
			<dt><strong>Πίνεις;</strong></dt>
			<dd>Με παρέα</dd>
			
			<dt><strong>Μόρφωση</strong></dt>
			<dd><?php
			echo $educations[ $theuser->Profile->Education ];
			?></dd>
			
			<dt><strong>Θρήσκευμα</strong></dt>
			<dd>Αγνωστικισμός</dd>
			
			<dt><strong>Πολιτική ιδεολογία</strong></dt>
			<dd>Δεξιά</dd>
		</dl><?php
	}
?>