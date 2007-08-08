<?php

	function UnitCommentsSpam( tInteger $commentid ) {
		global $user;
		global $libs;
		
		$libs->Load( 'comment' );
		
		if ( !$user->CanModifyCategories() ) {
			return;
		}
		
		$id = $commentid->Get();
		
		?>element = document.getElementById( 'comment_<?php
		echo $id;
		?>' );
		
        for ( i in element.childNodes ) {
            child = element.childNodes[ i ];
            if ( child.nodeType == 1 ) {
                child.style.display = 'none';
            }
        }
        
        loading = document.createElement( 'span' );
        loading.appendChild( document.createTextNode( 'Διαγραφή...' ) );
        
        element.appendChild( loading );<?php
		
		$comment = new Comment( $id );
		$comment->Kill();
		
		?>for ( i in loading.childNodes ) {
            loading.removeChild( loading.childNodes[ i ] );
        }
		
		loading.appendChild( document.createTextNode( 'Ban <?php
		echo $comment->Ip();
		?> για μία εβδομάδα...' ) );<?php
		
		User_IpBan( $comment->Ip(), "7" * 60 * 60 * 24 );
		
		?>for ( i in loading.childNodes ) {
            loading.removeChild( loading.childNodes[ i ] );
        }<?php
	}

?>
