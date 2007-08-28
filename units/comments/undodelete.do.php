<?php

	function UnitCommentsUndodelete( tInteger $commentid ) {
		global $user;
		global $libs;
		
        $commentid = $commentid->Get();
        
		$libs->Load( 'comment' );
		
		$comment = New Comment( $commentid );
		$daddy = $comment->ParentId();
		$comment->UndoDelete();
		
		?>var id = <?php echo $commentid; ?>;
		var daddy = <?php echo $daddy; ?>
		
		g( 'comment_' + id ).style.display = 'block';
		
		g( 'comment_undo_delete_' + id ).style.display = 'none';
		
		if( daddy != 0 ) {
			Comments.hideDeleteButton( daddy );
		}
		
		<?php
	}

?>
