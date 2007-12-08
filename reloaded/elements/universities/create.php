<?php
	function ElementUniversitiesCreate() {
		global $user;
		global $water;
		global $libs;
		global $page;
		global $xc_settings;
		
		$page->SetTitle( 'Διαχείριση πανεπιστημίων' );
		$page->AttachScript( 'js/universities.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/modal.js' );
		$page->AttachStyleSheet( 'css/modal.css' );
		$libs->Load( 'universities' );
		$libs->Load( 'place' );
		
		if ( !$user->CanModifyCategories() ) {
			die( 'Δεν έχεις δικαίωμα να δεις αυτή τη σελίδα' );
		}
		$allunis = Uni_Retrieve( 0 , false );
		$water->Trace( 'uni number: ' . count( $allunis ) );
		?><br /><br />
		<div class="body">
			<h3>Διαχείριση πανεπιστημίων</h3><br />
			<input id="uniname" type="text" style="width:300px" value="Γράψε εδώ το πανεπιστήμιο!" onclick="this.value = '';" /><br />
			<input type="radio" name="unitype" value="0" id="uniaei" checked="checked" /><label for="uniaei">ΑΕΙ</label>
			<input type="radio" name="unitype" value="1" id="unitei" /><label for="unitei" >ΤΕΙ</label><br />
			<select id="uni_area"><?php
				$places = AllPlaces();
				foreach( $places as $place ) {
					?><option value="<?php
					echo $place->Id;
					?>" id="<?php
					echo $place->Id;
					?>"><?php
					echo $place->Name;
					?></option><?php
				}
			?></select><br /><br />
			<input type="button" value="Δημιουργία" onclick="Uni.Create();return false;" /><br />			
			<br /><br />
			<div class="unilist" id="unilist"><?php
			foreach( $allunis as $uni ) {
				?><div id="uni<?php
				echo $uni->Id;
				?>"><?php
				echo htmlspecialchars( $uni->Name )
				?> - <?php
				if ( $uni->TypeId == 0 ) {
					?>AΕΙ - <?php
				}
				else {
					?>TΕΙ - <?php
				}
				echo $uni->Place->Name;
				?> <a href="" onclick="Uni.Edit( '<?php
				echo $uni->Id;
				?>' );return false;"><img src="<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>icons/edit.png" alt="Επεξεργασία" title="Επεξεργασία" /></a> 
				<a href="" onclick="Uni.Delete( '<?php
				echo $uni->Id;
				?>' );return false;"><img src="<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>icons/delete.png" alt="Διαγραφή" title="Διαγραφή" /></a>
				<span style="display:none" id="name<?php
				echo $uni->Id;
				?>"><?php
				echo htmlspecialchars( $uni->Name );
				?></span>
				<span style="display:none" id="type<?php
				echo $uni->Id;
				?>"><?php
				echo $uni->TypeId;
				?></span>
				<span style="display:none" id="place<?php
				echo $uni->Id;
				?>"><?php
				echo $uni->PlaceId;
				?></span></div><?php
			}
			?></div><?php
		?></div>
		<div id="testmodaluni" style="width:400px;height:200px;display:none"><br />
			<h5>Επεξεργασία</h5><br />
			<input type="text" id="modaluniname" style="width:300px;" /><br />
			<input type="radio" id="modaluniaei" name="modalunitype" /><label for="modaluniaei">ΑΕΙ</label>
			<input type="radio" id="modalunitei" name="modalunitype" /><label for="modalunitei">ΤΕΙ</label><br />
			<select id="modaluniplace"><?php
			foreach( $places as $place ) {
				?><option value="<?php
				echo $place->Id;
				?>" id="modaluniplace<?php
				echo $place->Id;
				?>"><?php
				echo $place->Name;
				?></option><?php
			}
			?></select><br /><br />
		</div><?php
		
	}
?>
