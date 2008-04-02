<?php 
	function ElementUserProfileSidebarLook( $theuser ) {
		$sex = array( 
			'm' => 'Άνδρας',
			'f' => 'Γυναίκα'
		);
		if ( $theuser->Gender != '-' ) {
			$showgender = true;
		}
		if ( $theuser->Profile->Weight != '' ) {
			$showweight = true;
		}
		if ( $theuser->Profile->Height != '' ) {
			$showheight = true;
		}
		?><ul><?php
			if ( $showgender ) {
				?><li><?php
				echo $sex[ $theuser->Gender ];
				?></li><?php
			}
			if ( ( $showgender && $showweight ) || ( $showgender && !$showweight && $showheight ) ) {
				?><li class="dot">·</li><?php
			}
			if ( $showheight ) {
				?><li><?php
				echo $theuser->Profile->Height / 100;
				?>m</li><?php
			}
			if ( $showweight && $showheight ) {
				?><li class="dot">·</li><?php
			}
			if ( $showweight ) {
				?><li><?php
				echo $theuser->Profile->Weight;
				?>kg</li><?php
			}
		?></ul><?php
	}
?>