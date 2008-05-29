<?php
	
	function UnitUserAvatar( tInteger $imageid ) {
		global $user;
		
		$image = New Image( $imageid->Get() );
		
		if ( !$image->IsDeleted() ) {
			if ( $image->User->Id == $user->Id ) {
				$egoalbum = New Album( $user->Egoalbumid );
				if ( $egoalbum->Mainimage != $image->Id ) {
					$egoalbum->Mainimage = $image->Id;
					$egoalbum->Save();
					?>alert( 'coala' );
					$( 'div.sidebar div.basicinfo h2 img' ).attr( {
						src : ExcaliburSettings.photosurl + '<?php
						echo $user->Id;
						?>/<?php
						echo $image->Id;
						?>?resolution=150x150x&sandbox=yes'
					} );
					$( $( 'div.main div.photos ul li a' )[ 0 ] ).attr( {
						href : '?p=photo&id=<?php
						echo $image->Id;
						?>'
					} ).html( <?php
					ob_start();
					Element( 'image' , $image , 100 , 100 , '' , $user->Name , $user->Name , '' );
					echo w_json_encode( ob_get_clean() );
					?> );
					$( $( 'div.main div.photos ul' )[ 0 ] ).show();<?php
				}
			}
		}
	}
?>
