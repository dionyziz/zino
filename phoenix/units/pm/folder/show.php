<?php
	function UnitPmFolderShow( tInteger $folderid ) {
		global $libs;
		global $user;
		
		$libs->Load( "pm/pm" );	
		
		$folderid = $folderid->Get();
		$folder = New PMFolder( $folderid );
		?>var deletelink = $( '#deletefolderlink' )[ 0 ];
		var renamelink = $( '#renamefolderlink' )[ 0 ];<?php
		if ( $folder->Typeid != PMFOLDER_USER ) {
			?>pms.messagescontainer.innerHTML = <?php
			ob_start();
			Element( 'pm/folder/view' , $folder );
			echo w_json_encode( ob_get_clean() );
			?>;
			$( deletelink ).hide().click( function() {
				return false;
			} );
			$( renamelink ).hide().click( function() {
				return false;
			} );
			pms.ShowFolderNameTop( '<?php 
			if ( $folder->Typeid == PMFOLDER_INBOX ) {
			//if ( $folderid == PMFOLDER_INBOX ) {
				?>Εισερχόμενα' );<?php
			}
			else {
				?>Απεσταλμένα' );<?php
			}
		}
		else {
			if ( $folder->Userid == $user->Id ) {
				?>$( deletelink ).css( "display" , "block" );
				deletelink.onclick = ( function( folderid ) {
					return function() {
						pms.DeleteFolder( folderid );
						return false;
					}
				})( <?php 
				echo $folderid;
				?> );
				$( renamelink ).css( "display" , "block" );
				renamelink.onclick = ( function( folderid ) {
					return function () {
						pms.RenameFolder( folderid );
						return false;
					}
				} )( <?php
				echo $folderid;
				?> );
				pms.messagescontainer.innerHTML = <?php
				ob_start();
				Element( 'pm/folder/view' , $folder );
				echo w_json_encode( ob_get_clean() );
				?>;
				pms.ShowFolderNameTop( <?php
				echo w_json_encode( $folder->Name );
				?> );<?php
			}
		}
		?>$( 'div.message' ).draggable( { 
				helper : 'original',
				revert : 'true',
				cursor : 'move'
		} );<?php
	}
?>
