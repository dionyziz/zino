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
		var daddy = <?php echo $daddy; ?>;
		
		g( 'comment_' + id ).style.display = 'block';
		
		g( 'comment_undo_delete_' + id ).style.display = 'none';
		
		if( daddy != 0 ) {
			var numcom = g( daddy + "_children" ).firstChild;
			var num = parseInt( numcom.nodeValue );
			++num;
			numcom.nodeValue = num;
			
			if( num == 1 ) {
				var toolbar = g( 'comment_' + daddy + '_toolbar' );
				for( var i in toolbar.childNodes ) {
					if( toolbar.childNodes[i].firstChild.firstChild.firstChild.nodeValue == "Διαγραφή" ) {
						toolbar.removeChild( toolbar.childNodes[i] );
						break;
					}
				}
			}
		}
		
		<?php
	}

?>
