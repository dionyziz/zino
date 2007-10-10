<?php
	function UnitUniversitiesSet( tInteger $uniid ) {
		global $user;
		global $water;
		
		?>alert( 'coala saving' );<?php
		$uniid = $uniid->Get();
		$uni = new Uni( $uniid );
		$user->SetUni( $uniid );
		?>alert( '<?php echo $uniid; ?>' );
		var uniname = document.getElementById( 'uniname' );
		var newtext = document.createTextNode( <?php
		echo w_json_encode( $uni->Name );
		?> + ' - ' + <?php
		echo w_json_encode( $uni->Place->Name );
		?> + ' ' );
		var editimg = document.createElement( 'img' );
		editimg.src = 'http://static.chit-chat.gr/images/icons/edit.png';
		editimg.alt = 'Επεξεργασία';
		editimg.title = 'Επεξεργασία';
		var editlink = document.createElement( 'a' );
		editlink.href = '';
		editlink.onclick = ( function() {
			return function() {	
				Uni.SetUni();
				return false;
			}
		});
		editlink.appendChild( editimg );
		uniname.appendChild( newtext );
		uniname.appendChild( editlink );
		Modals.Destroy();<?php	
	}
?>