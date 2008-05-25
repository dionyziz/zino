<?php
	
	function UnitUserSettingsUpload( tInteger $imageid ) {
		$image = New Image( $imageid->Get() );
		
		?>$( $( 'div.modal div.avatarlist ul li' )[ 0 ] ).html( <?php
		ob_start();
		Element( 'user/settings/personal/photosmall' , $image );
		echo w_json_encode( ob_get_clean() );
		?> ).show();<?php
		if ( $image->Album->Numphotos == 1 ) {
			?>Coala.Warm( 'user/settings/avatar' , { imageid : <?php
			echo $image->Id;
			?> } );<?php
		}
	}
?>
