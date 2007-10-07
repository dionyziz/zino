<?php

	function ElementUserProfileCharacteristics( $theuser ) {
		global $libs;
		global $user;
		global $page;
		
		$page->AttachScript( "js/options.js" );
		
		if ( $user->Id() == $theuser->Id() || $theuser->Height() != "" || $theuser->Weight() != "" || $theuser->EyeColor() != "" || $theuser->HairColor() != "" ) {
		
			if ( $user->Id() == $theuser->Id() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
				ob_start();
				?>ProfileOptions.Init( g( "user_profile_characteristics" ) );<?php
				$page->AttachInlineScript( ob_get_clean() );
			}
			
			?><div class="characteristics">
				<h4 style="position: relative;">εξωτερικά χαρακτηριστικά <?php
				// <img style="position: absolute; right: 5px" src="http://static.chit-chat.gr/images/icons/disk.png" />
				?></h4>
	            <ul id="user_profile_characteristics"><?php
					$count = 2; // Just a number to help for the divisions
					if ( $theuser->Height() != "" || $user->Id() == $theuser->Id() ) {
		                ?><li><dl <?php
							if( $count%2 != 0 ) {
								?> class="l"<?php
							}
							?>>
		                    <dt>ύψος</dt>
		                    <dd id="user_options_height"><?php
								echo htmlspecialchars( $theuser->Height() );
							?></dd>
		                </dl></li><?php
						++$count;
					}
	                
					if ( $theuser->Weight() != "" || $user->Id() == $theuser->Id() ) {
						?><li><dl <?php
							if( $count%2 != 0 ) {
								?> class="l"<?php
							}
							?>>
		                    <dt>βάρος</dt>
		                    <dd id="user_options_weight"><?php
								echo htmlspecialchars( $theuser->Weight() );
							?></dd>
		                </dl></li><?php
						++$count;
					}
					
					if ( $theuser->EyeColor() != "" || $user->Id() == $theuser->Id() ) {
		                ?><li><dl <?php
							if( $count%2 != 0 ) {
								?> class="l"<?php
							}
							?>>
		                    <dt>χρώμα ματιών</dt>
		                    <dd id="user_options_eyecolor"><?php
								echo htmlspecialchars( $theuser->EyeColor() );
							?></dd>
		                </dl></li><?php
						++$count;
					}
					
					if ( $theuser->HairColor() != "" || $user->Id() == $theuser->Id() ) {
		                ?><li><dl <?php
							if( $count%2 != 0 ) {
								?> class="l"<?php
							}
							?>>
		                    <dt>χρώμα μαλλιών</dt>
		                    <dd id="user_options_haircolor"><?php
								echo htmlspecialchars( $theuser->HairColor() );
							?></dd>
		                </dl></li><?php
						++$count;
					}
					
	            ?></ul>
			</div><?php
		}
	}
	
?>
