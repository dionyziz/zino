<?php
	function ElementUserProfileFriends( $friends, $uid, $tags ) {
		//$friends = $theuser->GetFriends(); 
		//if ( count( $friends ) > 0 ) {
		if( $tags ) { // This element is also used by elements/interesttag/view.php to show people with common interests
			?><b>Ενδιαφέροντα</b><?php
		}
		else {
			?>Οι φίλοι μου<?php
		}
		?> <br />
			<div id="friends"><?php
			foreach ( $friends as $friend ) { 
				?><div style="width:70%;" id="friend_<?php
					echo $friend->Id(); 
					?>">
					<div class="opties">
						<div class="upperline">
							<div class="leftupcorner"></div>
							<div class="rightupcorner"></div>
							<div class="middle"></div>
						</div>
						<div class="rectanglesopts" style="min-height:70px;"><br /><?php
							Element( 'user/display' , $friend );
                            ?><br /><?php
							if ( $friend->Hobbies() != "" ) { 
								?><div style="overflow:hidden;width:90%;" title="Ενδιαφέροντα"><b>Ενδιαφέροντα:</b> <?php
								echo htmlspecialchars( $friend->Hobbies() ); 
                                ?></div><?php
							}
							if ( $friend->Id() != $uid ) {
							?>
							<div style="overflow:hidden;width:90%;" title="Σχέση"><b>Σχέση:</b> <?php
							if( !$friend->Frel_type() ) {
								$rtype = "Καμία";
							}
							else {
								$rtype = $friend->Frel_type();
							}
							echo $rtype;
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
			?></div><?php
		//}
	}
?>
