<?php
	function ElementUserProfileFans( $theuser , $fans ) {
		global $user;
		
		//$fans = $theuser->GetFans();
		//if ( count( $fans ) > 0 ) {
			?>Με έχουν φίλ<?php
			if ( $theuser->Gender() == '' ) {
				?>η<?php
			}
			else {
				?>o<?php
			}
			?> <br />
			<div id="fans"><?php
			
			foreach ( $fans as $fan ) {
				//$fan = New User( $fan->Id() );?>
				<div style="width:70%;" id="fan_<?php
					echo $fan->Id(); 
					?>">
					<div class="opties">
						<div class="upperline">
							<div class="leftupcorner"></div>
							<div class="rightupcorner"></div>
							<div class="middle"></div>
						</div>
						<div class="rectanglesopts" style="min-height:70px;" <?php
						if ( $user->Id() == $fan->Id() ) { 
							?>id="newfancontent" <?php
						}
						?>><br /><?php
							Element( 'user/display' , $fan );
							?><br /><?php
							if ( $fan->Hobbies() != "" ) {
								?><div style="overflow:hidden;width:90%;"><b>Ενδιαφέροντα:</b> <?php
								echo htmlspecialchars( $fan->Hobbies() );
								?></div><br /><?php
							}
						?></div>
						<div class="downline">
							<div class="leftdowncorner"></div>
							<div class="rightdowncorner"></div>
							<div class="middledowncss"></div>
						</div>
					</div>
				</div><?php
			}
			if ( !( $user->Id() == $theuser->Id() ) && !$user->IsFriend( $theuser->Id() ) ) { 
				?><div style="width:70%; display:none" id="newfan">
					<div class="opties">
						<div class="upperline">
							<div class="leftupcorner"></div>
							<div class="rightupcorner"></div>
							<div class="middle"></div>
						</div>
						<div id="newfancontent" class="rectanglesopts" style="min-height:70px;">
						</div>
						<div class="downline">
							<div class="leftdowncorner"></div>
							<div class="rightdowncorner"></div>
							<div class="middledowncss"></div>
						</div>
					</div>
				</div><?php
			}
			?></div><?php
	}
?>
