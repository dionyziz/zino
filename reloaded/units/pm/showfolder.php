<?php
    function UnitPmShowfolder( tInteger $folderid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( "pm" );	
    	
    	$folderid = $folderid->Get();
    	?>var deletelink = $( '#deletefolderlink' )[ 0 ];
    	var renamelink = $( '#renamefolderlink' )[ 0 ];<?php
    	if ( $folderid == -1 || $folderid == -2 ) {
    		?>pms.messagescontainer.innerHTML = <?php
    		ob_start();
    		Element( 'pm/showfolder' , $folderid );
    		echo w_json_encode( ob_get_clean() );
    		?>;
			$( deletelink ).hide().click( function() {
				return false;
			} );
			$( renamelink ).hide().click( function() {
				return false;
			} );
			/*
    		deletelink.style.display = 'none';
    		deletelink.onclick = function() {
    			return false;
    		};
            renamelink.style.display = 'none';
            renamelink.onclick = function () {
                return false;
            };
			*/
    		pms.ShowFolderNameTop( '<?php 
    		if ( $folderid == - 1 ) {
    			?>Εισερχόμενα' );<?php
    			$unreadmsgs = PM_UserCountUnreadPms( $user );
    			if ( $unreadmsgs > 0 ) {
    				?>pms.UpdateUnreadPms( <?php
    				echo $unreadmsgs;
    				?> );<?php
    			}
    		}
    		else {
    			?>Απεσταλμένα' );<?php
    		}
    	}
    	else {
    		$folder = PMFolder_Factory( $folderid );
    		if ( $folder->UserId == $user->Id() ) {
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
    			Element( 'pm/showfolder' , $folder );
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