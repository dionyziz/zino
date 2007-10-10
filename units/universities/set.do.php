<?php
	function UnitUniversitiesSet( tInteger $uniid ) {
		global $user;
		global $water;
		
		?>alert( '<?php echo $uniid->Get() ?>' );<?php
		$user->SetUni( $uniid->Get() );
	}