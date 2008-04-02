<?php
	function ElementUserProfileSidebarSocial( $theuser ) {
		$education = array( 
				'elementary' => 'Δημοτικό',
				'gymnasium' => 'Γυμνάσιο',
				'TEE' => 'ΤΕΕ',
				'lyceum' => 'Λύκειο',
				'ΤΕΙ' => 'TEI',
				'university' => 'Πανεπιστήμιο'
		);
		if ( $theuser->Gender == 'm' || $theuser->Gender == '-' ) {
			$religions = array( 
				'christian' => 'Χριστιανός',
				'muslim' => 'Ισλαμιστής',
				'atheist' => 'Άθεος',
				'agnostic' => 'Αγνωστικιστής',
				'nothing' => 'Καμία'
			);
		}
		else {
			$religions = array( 
				'christian' => 'Χριστιανή',
				'muslim' => 'Ισλαμίστρια',
				'atheist' => 'Άθεη',
				'agnostic' => 'Αγνωστικιστής',
				'nothing' => 'Καμία'
			);
		}
		if ( $theuser->Gender == 'm' || $theuser->Gender == '-' ) {
			$politics = array( 
				'right' => 'Δεξιός',
				'left' => 'Αριστερός',
				'center' => 'Κεντρώος',
				'radical left' => 'Ακροαριστερός',
				'radical right' => 'Ακροδεξιός',
				'nothing' => 'Τίποτα'
			);
		}
		else {
			$politics = array( 
				'right' => 'Δεξιά',
				'left' => 'Αριστερή',
				'center' => 'Κεντρώα',
				'radical left' => 'Ακροαριστερή',
				'radical right' => 'Ακροδεξιά',
				'nothing' => 'Τίποτα'
			);
		}
		?><dl>
			<dt><strong>Σεξουαλικές προτιμήσεις</strong></dt>
			<dd>Gay</dd>
			
			<dt><strong>Καπνίζεις;</strong></dt>
			<dd>Όχι</dd>
			
			<dt><strong>Πίνεις;</strong></dt>
			<dd>Με παρέα</dd>
			
			<dt><strong>Μόρφωση</strong></dt>
			<dd><?php
			echo $education[ $theuser->Profile->Education ];
			?></dd>
			
			<dt><strong>Θρήσκευμα</strong></dt>
			<dd><?php
			echo $religions[ $theuser->Profile->Religion ];
			?></dd>
			
			<dt><strong>Πολιτική ιδεολογία</strong></dt>
			<dd><?php
			echo $politics[ $theuser->Profile->Politics ];
			?></dd>
		</dl><?php
	}
?>