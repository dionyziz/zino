<?php
	function ElementUserProfileFriends( $theuser , $friends ) {
		//$friends = $theuser->GetFriends(); 
		//if ( count( $friends ) > 0 ) {
			?>Οι φίλοι μου <br />
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
                                ?></div><br /><?php
							}
							?>
							<div style="overflow:hidden;width:90%;" title="Σχέση"><b>Σχέση:</b> <?php
							echo $friend->Frel_type();
							?></div><br />
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
		//}
	}
?>
