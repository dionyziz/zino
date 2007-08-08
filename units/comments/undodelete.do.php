<?php

	function UnitCommentsUndodelete( tInteger $commentid ) {
		global $user;
		global $libs;
		
        $commentid = $commentid->Get();
        
		$libs->Load( 'comment' );
		
		$comment = New Comment( $commentid );
		$comment->UndoDelete();
		
		?>id = <?php echo $commentid; ?>;
		g( 'comment_' + id ).style.display = 'block';
		
		g( 'comment_undo_delete_' + id ).style.display = 'none';<?php
	}

?>