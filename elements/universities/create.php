<?php
	function ElementUniversitiesCreate() {
		global $user;
		global $water;
		global $libs;
		global $page;
		
		$page->SetTitle( 'Διαχείριση πανεπιστημίων' );
		$page->AttachScript( 'js/universities.js' );
		$libs->Load( 'universities' );
		
		if ( !$user->CanModifyCategories() ) {
			die( 'Δεν έχεις δικαίωμα να δεις αυτή τη σελίδα' );
		}
		$allunis = Uni_Retrieve( 0 , false );
		$water->Trace( 'uni number: ' . count( $allunis ) );
		?><br /><br />
		<div class="body">
			<h3>Διαχείριση πανεπιστημίων</h3><br />
			<form method="" onkeypress="return submitenter(this, event);" id="createform" onsubmit="Uni.Create();return false;">
				<input id="uniname" type="text" style="width:300px" value="Γράψε εδώ το πανεπιστήμιο!" onclick="this.value = '';" /> 
				<input type="button" value="Δημιουργία" onclick="Uni.Create();return false;" /><br />
				<input id="uniaei" type="radio" name="unitype" value="0">ΑΕΙ</input><br />
				<input id="unitei" type="radio" name="unitype" value="1">ΤΕΙ</input>			
			</form><br /><br />
			<div class="unilist" id="unilist"><?php
			foreach( $allunis as $uni ) {
				?><div><?php
				echo htmlspecialchars( $uni->Name );
				?></div><?php
			}
			?></div><?php
		?></div><?php
		
	}
?>