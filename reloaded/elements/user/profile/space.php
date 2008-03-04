<?php
	function ElementUserProfileSpace( $theuser ) {
		global $user;
		global $libs;
        global $xc_settings;
		
        $libs->Load( 'userspace' );

		if ( $theuser->Blog() == 0 ) {
			if ( $user->Id() == $theuser->Id() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
				?><br /><a style="text-decoration:none" href="do/user/space/activate">
				<img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/edit.png" alt="" /> Επεξεργασία χώρου</a><?php
			}
			return;
		}
		else if ( $user->Id() == $theuser->Id() && $user->Rights() >= $xc_settings[ 'readonly' ] ) { 
			?><br />
			<a style="text-decoration:none" href="index.php?p=editspace"><img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>icons/edit.png" alt="" /> Επεξεργασία χώρου</a><?php
		}
		?><br /><br /><?php
		?><div style="text-align: center;"><?php
			$userspace = New Userspace( $theuser );
			echo $userspace->Text();
		?></div><?php
    }
?>
