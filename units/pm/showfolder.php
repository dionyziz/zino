<?php
    function UnitPmShowfolder( tInteger $folderid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( "pm" );	
    	
    	$folderid = $folderid->Get();
    	?>var deletelink = document.getElementById( 'deletefolderlink' );<?php
    	if ( $folderid == -1 || $folderid == -2 ) {
    		?>pms.messagescontainer.innerHTML = <?php
    		ob_start();
    		Element( 'pm/showfolder' , $folderid );
    		echo w_json_encode( ob_get_clean() );
    		?>;
    		deletelink.style.display = 'none';
    		deletelink.onclick = ( function() {
    			return false;
    		})();
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
    			?>deletelink.style.display = 'block';
    			deletelink.onclick = ( function( folderid ) {
    				return function() {
    					pms.DeleteFolder( folderid );
    					return false;
    				}
    			})( <?php 
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
        ?>var dmessages = pms.messagescontainer.getElementsByTagName('div');
        var dfolders = document.getElementById('folders').getElementsByTagName('div');
        for ( i = 0; i < dmessages.length; ++i ) {
            dmessage = dmessages[ i ];
            if ( dmessage.className == 'message' ) { // message
                var drag = Drag.Create( dmessage );
                drag.SetOnStart( function ( draggable ) {
                    var mdiv = document.createElement( 'div' );
                    mdiv.style.width = draggable.offsetWidth + 'px';
                    mdiv.style.height = draggable.offsetHeight + 'px';
                    mdiv.style.backgroundColor = 'white';
                    mdiv.style.paddingBottom = '15px';
                    draggable.parentNode.insertBefore( mdiv, draggable );
                    draggable.style.position = 'absolute';
                    draggable.style.zIndex = '5';
                    Animations.Create( draggable, 'opacity', 500, 1, 0.7 );
                    document.getElementById( 'folders' ).style.border = '1px solid black';
                } );
                drag.SetOnEnd( function ( draggable ) {
                    draggable.parentNode.removeChild( draggable.previousSibling );
                    draggable.style.position = 'relative';
                    draggable.style.left = '0';
                    draggable.style.zIndex = '0';
                    draggable.style.top = '0';
                    Animations.Create( draggable, 'opacity', 500, 0.7, 1 );
                    document.getElementById( 'folders' ).style.border = '1px solid #838080';
                } );
                for ( j = 0; j < dfolders.length; ++j ) {
                    if ( dfolders[ j ].id.substr( 0, 'folder_'.length ) == 'folder_' ) {
                        drag.AddDroppable( dfolders[ j ] );
                    }
                }
                drag.SetOnOver( function ( draggable, droppable ) {
                    droppable.style.backgroundColor = '#d6e6f7';
                    droppable.style.fontWeight = 'bold';
                } );
                drag.SetOnOut( function ( draggable, droppable ) {
                    droppable.style.backgroundColor = 'inherit';
                    droppable.style.fontWeight = 'inherit';
                } );
                drag.SetOnDrop( function ( draggable, droppable ) {
                    Coala.Warm( 'pm/move', {
                        'pmid': draggable.substr( 'pm_'.length ),
                        'targetfolderid': droppable.substr( 'folder_'.length )
                    } );
                    draggable.parentNode.removeChild( draggable );
                } );
            }
        }<?php
    }
?>