<?php
	function UnitUniversitiesSet( tInteger $uniid , tBoolean $unset ) {
		global $user;
		global $water;
		global $libs;
		global $xc_settings;
		
		$libs->Load( 'universities' );
		
		$uniid = $uniid->Get();
		$unset = $unset->Get();
		if ( !$unset ) {
			$uni = new Uni( $uniid );
		}
		if ( $unset ) {
			$user->SetUni( 0 );
		}
		else {
			$user->SetUni( $uniid );
		}
		?>var uniname = document.getElementById( 'uniname' );
		while( uniname.firstChild ) {
			uniname.removeChild( uniname.firstChild );
		}<?php
		if ( !$unset ) {
			?>var newtext = document.createTextNode( <?php
			echo w_json_encode( $uni->Name );
			?> + ' - ' + <?php
			echo w_json_encode( $uni->Place->Name );
			?> + ' ' );
			var editimg = document.createElement( 'img' );
			editimg.src = '<?php
			echo $xc_settings[ 'staticimagesurl' ];
			?>
			icons/edit.png';
			editimg.alt = 'Επεξεργασία';
			editimg.title = 'Επεξεργασία';
			var editlink = document.createElement( 'a' );
			editlink.href = '';
			editlink.onclick = ( function() {
				return function() {	
					Uni.SetUni();
					return false;
				};
			})();
			var dellink = document.createElement( 'a' );
			var delimg = document.createElement( 'img' );
			delimg.src = '<?php
			echo $xc_settings[ 'staticimagesurl' ];
			?>
			icons/delete.png';
			delimg.alt = 'Διαγραφή';
			delimg.title = 'Διαγραφή';
			dellink.href = '';
			dellink.style.marginLeft = '2px';
			dellink.onclick = ( function() {
				return function() {
					Uni.UnsetUni();
					return false;
				}
			})();
			dellink.appendChild( delimg );
			editlink.appendChild( editimg );
			uniname.appendChild( newtext );
			uniname.appendChild( editlink );
			uniname.appendChild( dellink );<?php
		}
		else {
			?>var newtext = document.createElement( 'a' );
			newtext.appendChild( document.createTextNode( 'Είσαι φοιτητής;' ) );
			newtext.href = '';
			newtext.onclick = ( function() {
				return function() {
					Uni.SetUni();
					return false;
				}
			})();
			uniname.appendChild( newtext );<?php
		}
		?>
		Modals.Destroy();<?php	
	}
?>
