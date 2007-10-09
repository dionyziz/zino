<?php
	function UnitUniversitiesCreate( tString $uniname , tInteger $typeid , tInteger $placeid ) {
		global $libs;
		global $water;
		global $user;
		
		$libs->Load( 'universities' );
		
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		$uniname = $uniname->Get();
		$typeid = $typeid->Get();
		$placeid = $placeid->Get();
		?>alert( "uni name: <?php echo $uniname;?>, typeid: <?php echo $typeid;?>, placeid: <?php echo $placeid;?>" );<?php	
	}
?>