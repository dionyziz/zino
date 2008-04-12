<?php

	function UnitUserSettingsSave( tInteger $dobd , tInteger $dobm , tInteger $doby , tString $gender , tInteger $place , tString $education , tString $sex , tString $religion , tString $politics , tString $aboutme ) {
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
				?>alert( '<?php echo $aboutme; ?>' );<?php
			}
			?>$( Settings.showsaving )
				.animate( { opacity : "0" } , 200 , function() {
				$( Settings.showsaving ).css( "display" , "none" );
				$( Settings.showsaved )
					.css( "display" , "block" )
					.css( "opacity" , "1" )
					.animate( { opacity : "0" } , 1500 , function() {
						$( Settings.showsaved ).css( "display" , "none" ).css( "opacity" , "0" );
					});
			});
			<?php
		}
	}
?>
