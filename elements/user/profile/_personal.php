<?php
	function ElementUserProfilePersonal( $theuser ) {
		global $libs;
		global $user;
		global $page;
		
		$libs->Load( 'place' );
        $libs->Load( 'interesttag' );

        $page->AttachScript( 'js/interesttag.js' );
		
		static $monthsfordob = array(
									1  => 'Ιανουαρίου',
									2  => 'Φεβρουαρίου',
									3  => 'Μαρτίου',
									4  => 'Απριλίου',
									5  => 'Μαίου',
									6  => 'Ιουνίου',
									7  => 'Ιουλίου',
									8  => 'Αυγούστου',
									9  => 'Σεπτεμβρίου',
									10 => 'Οκτωβρίου',
									11 => 'Νοεμβρίου',
									12 => 'Δεκεμβρίου'
								);

		if ( $theuser->Gender() != "-" ) {
			switch ( $theuser->Gender() ) {
				case "male":
					$gn = "άνδρας";
					$artcl ="Ο";
					$artclgen = "του";
					break;
				case "female":
					$gn = "γυναίκα";
					$artcl = "Η";
					$artclgen = "της";
					break;
			}
			if ( $theuser->Id() == 1 ) {
				$gn = "Αγόρι";
			}
		}
        $validdob = false;
		if ( $theuser->DateOfBirth() != "0000-00-00" ) {
			if ( $theuser->DateOfBirthYear() != "2005" ) {
				$validdob = true;
				$nowdate = getdate();
				$nowyear = $nowdate[ "year" ];
				$ageyear = $nowyear - $theuser->DateOfBirthYear();
				$nowmonth = $nowdate[ "mon" ];
				$nowday = $nowdate[ "mday" ];
				$hasbirthday = false;
				if ( $nowmonth < $theuser->DateOfBirthMonth() ) {
					--$ageyear;
				}
				else {
					if ( $nowmonth == $theuser->DateOfBirthMonth() ) {
						if ( $nowday < $theuser->DateOfBirthDay() ) {
							--$ageyear;
						}
						else {
							if ( $nowday == $theuser->DateOfBirthDay() ) {
								$hasbirthday = true;
							}
						}
					}
				}
			}
		}
		
		if ( !isset( $gn ) && !$validdob && !$theuser->Place() && !$theuser->Hobbies() ) { // if there's no info to display
			return;
		}
		
			
		if ( $user->Id() == $theuser->Id() ) {
			ob_start();
			// ProfileOptions.Init( g( "user_profile_personal" ) );
			$page->AttachInlineScript( ob_get_clean() );
		}
		
		?><div class="personal">
			<h4>προσωπικές πληροφορίες</h4>
			<ul id="user_profile_personal"><?php
				if ( $theuser->Gender() != "-" ) {
					?><li><dl>
						<dt>φύλο</dt>
						<dd id="user_options_gender"><?php
						echo $gn; 
						?></dd>
					</dl></li><?php
				}
				if ( $validdob ) { 
					?><li><dl<?php
					if ( $theuser->Gender() != "-" ) {
						?> class="l"<?php
					}
						?>><dt>ηλικία</dt>
						<dd><?php
							echo $ageyear;

							if ( $theuser->Id() == $user->Id() ) {
								?><a href="faq/age_shown" style="margin-left: 20px; font-size: 90%;">Πώς ξέρετε την ηλικία μου?</a><?php
							}
						?></dd>
					</dl></li><?php
				}
				if ( $validdob ) { 
					?><li><dl<?php
					if ( $theuser->Gender() == "-" ) {
						?> class="l"<?php
					}
						?>><dt>γενέθλια</dt>
						<dd><?php
						if ( $hasbirthday ) { 
							?>Έχει σήμερα γενέθλια και γίνεται <?php
							echo $ageyear;
						}
						else { 
							echo $theuser->DateOfBirthDay() . " " . $monthsfordob[ intval( $theuser->DateOfBirthMonth() ) ];
						} 
						?></dd>
					</dl></li><?php
				}
				if ( $theuser->Place() ) { 
					?><li><dl<?php
					if ( $theuser->Gender() != "-" ) {
						?> class="l"<?php
					}
						?>><dt>περιοχή</dt>
						<dd><?php
							echo $theuser->Location(); 
						if ( $user->CanModifyCategories() ) { 
						?> (<a href="?p=places">Διαχείριση Περιοχών</a>)<?php
						} ?></dd>
					</dl></li><?php
				}

                $tags = InterestTag_List( $theuser );
				if ( !empty( $tags ) || $user->Id() == $theuser->Id() ) {
					?><li><dl<?php
					if ( ( $theuser->Gender() != "-" && $theuser->Place() == 0 ) || ( $theuser->Gender() == "-" && $theuser->Place() != 0 ) ) {
						?> class="l"<?php
					}
						?>><dt>ενδιαφέροντα</dt>
						<dd><?php
                            foreach ( $tags as $tag ) {
                                echo htmlspecialchars( $tag->Text ) . "&nbsp;";
                            }
                            ?> <input type="text" name="newinteresttag" onkeypress="return InterestTag.Submit( event );" />
						</dd>
					</dl></li><?php
				} 
				?>
			</ul>
		</div><?php
	}
?>
