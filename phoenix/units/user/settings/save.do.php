<?php

	function UnitUserSettingsSave( tInteger $dobd , tInteger $dobm , tInteger $doby , tString $gender , tInteger $place , tString $education , tString $sex , tString $religion , tString $politics , tString $aboutme , tString $haircolor , tString $eyecolor , tInteger $height , tInteger $weight , tString $smoker , tString $drinker , tString $email , tString $msn , tString $gtalk , tString $skype , tString $yahoo , tString $web ) {
		global $user;

		if ( $user->Exists() ) {
			$dobd = $dobd->Get();
			$dobm = $dobm->Get();
			$doby = $doby->Get();
			$gender = $gender->Get();
			$place = $place->Get();
			$education = $education->Get();
			$sex = $sex->Get();
			$religion = $religion->Get();
			$politics = $politics->Get();
			$aboutme = $aboutme->Get();
			$haircolor = $haircolor->Get();
			$eyecolor = $eyecolor->Get();
			$height = $height->Get();
			$weight = $weight->Get();
			$smoker = $smoker->Get();
			$drinker = $drinker->Get();
			$email = $email->Get();
			$msn = $msn->Get();
			$gtalk = $gtalk->Get();
			$skype = $skype->Get();
			$yahoo = $yahoo->Get();
			$web = $web->Get();
			
			if ( $dobd >=1 && $dobd <=31  && $dobm >= 1&& $dobm <= 12 && $doby ) {
				if ( strtotime( $doby . '-' . $dobm . '-' . $dobd ) ) {
					?>alert( '<?php echo $doby . '-' . $dobm . '-' . $dobd; ?>' );<?php
				}
			}
			if ( $gender ) {
				?>alert( '<?php echo $gender; ?>' );<?php
			}
			if ( $place ) {
				?>alert( '<?php echo $place; ?>' );<?php
			}
			if ( $education ) {
				?>alert( '<?php echo $education; ?>' );<?php
			}
			if ( $sex ) {	
				?>alert( '<?php echo $sex; ?>' );<?php
			}
			if ( $religion ) {
				?>alert( '<?php echo $religion; ?>' );<?php
			}
			if ( $politics ) {
				?>alert( '<?php echo $politics; ?>' );<?php
			}
			if ( $aboutme ) {
				if ( $aboutme == '-1' ) {
				
				}
				else {
					?>alert( '<?php echo $aboutme; ?>' );<?php
				}
				//if aboutme == -1 then save the empty string
			}
			if ( $haircolor ) {
				?>alert( '<?php echo $haircolor; ?>' );<?php
			}
			if ( $eyecolor ) {
				?>alert( '<?php echo $eyecolor; ?>' );<?php
			}
			if ( $height == -1 || $height == -2 || $height == -3 || $height >= 120 && $height <= 220 ) {
				?>alert( '<?php echo $height; ?>' );<?php
			}
			if ( $weight == -1 || $weight == -2 || $weight == -3 || $weight >= 20 && $weight <= 150 ) {
				?>alert( '<?php echo $weight; ?>' );<?php
			}
			if ( $smoker ) {
				?>alert( '<?php echo $smoker; ?>' );<?php
			}
			if ( $drinker ) {
				?>alert( '<?php echo $drinker; ?>' );<?php
			}
			if ( $email ) {
				if ( $email == '-1' ) {
					?>alert( 'save no email' );<?php
				}
				else {
					if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $email ) ) {
						?>alert( 'email <?php echo $email; ?>' );<?php
					}
					else {
						$emailerror = true;
						?>$( 'div#email span' ).css( "display" , "inline" )
						.animate( { opacity: "1"} , 200 , function() {
							Settings.invalidemail = true;
						} );<?php
					}
				}
				//if email == -1 save empty
			}
			if ( $msn ) {
				if ( $msn == '-1' ) {
					?>alert( 'save no msn' );<?php
				}
				else {
					if ( preg_match( '#^[a-zA-Z0-9.\-_]+@[a-zA-Z0-9.\-_]+$#', $msn ) ) {
						?>alert( 'msn <?php echo $msn; ?>' );<?php
					}
					else {
						$msnerror = true;
						?>$( 'div#msn span' ).css( "display" , "inline" )
						.animate( { opacity: "1"} , 200 , function() {
							Settings.invalidmsn = true;
						} );<?php
					}
				}
			}
			if ( $gtalk ) {
				?>alert( '<?php echo $gtalk; ?>' );<?php
			
			}
			if ( $skype ) {
				?>alert( '<?php echo $skype; ?>' );<?php
			}
			if ( $yahoo ) {
				?>alert( '<?php echo $yahoo; ?>' );<?php
			}
			if ( $web ) {
				?>alert( '<?php echo $web; ?>' );<?php
			}
			?>$( Settings.showsaving ).animate( { opacity : "0" } , 200 , function() {
				$( Settings.showsaving ).css( "display" , "none" );
			});<?php
			if ( !$emailerror && !$msnerror ) {
				?>$( Settings.showsaved )
					.css( "display" , "block" )
					.css( "opacity" , "1" )
					.animate( { opacity : "0" } , 1500 , function() {
						$( Settings.showsaved ).css( "display" , "none" ).css( "opacity" , "0" );
					});
				});
				<?php
			}
		}
	}
?>
