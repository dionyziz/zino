<?php
	function UnitCommentsDelete( tInteger $commentid ) {
		global $user;
		global $libs;
		
		$commentid = $commentid->Get();
		
		$libs->Load( 'comment' );
		
		$comment = New Comment( $commentid );
		if ( !$comment->Exists() ) {
			?>alert( 'Το σχόλιο που προσπαθήτε να διαγράψετε δεν υπάρχει' );
			window.location.reload();<?php
			return;
		}
		if ( $comments->IsDeleted() ) {
			?>alert( 'To σχόλιο που προσπαθήτε να διαγράψετε έχει ήδη διαγραφεί' );
			window.location.reload();<?php
			return;
		}
		if ( $user->Id != $comment->Userid && !$user->HasPermission( PERMISSION_COMMENT_DELETE_ALL ) ) {
			?>alert( 'Δεν έχετε δικαίωμα να διαγράψετε το συγκεκριμένο σχόλιο' );
			window.location.reload();<?php
			return;
		}
		$finder = New CommentFinder();
		if ( $finder->CommentHasChildren( $comment ) ) {
			?>alert( 'Το σχόλιο που προσπαθήτε να διαγράψετε έχει απαντήσεις' );
			window.location.reload();<?php
			return;
		}
		$comment->Delete();
	}	
/*
	function UnitCommentsDelete( tInteger $commentid ) {
		global $user;
		global $libs;
		
        $commentid = $commentid->Get();
        
		$libs->Load( 'comment' );
		
		$comment = New Comment( $commentid );
		$daddy = $comment->ParentId();
		$change = $comment->Kill();
		switch( $change ) {
			case 1: // OK
				?>var id = <?php echo $commentid; ?>;
				var daddy = <?php echo $daddy; ?>;
				var comment = document.getElementById( 'comment_loading_delete_' + id );
				comment.style.display = "none";
				
				var undo = d.createElement( "div" );
				undo.id = 'comment_undo_delete_' + id;
				undo.style.width = '100%';
				undo.style.textAlign = 'center';
				undo.style.paddingBottom = '5px';
				undo.style.cursor = 'pointer';
				undo.appendChild( d.createTextNode( "Το σχόλιο διεγράφη. " ) );
				
				var undoLink = d.createElement( "a" );
				undoLink.onclick = ( function( id ) { return function() { Comments.UndoDelete( id ); return false; } } )( id );
				undoLink.appendChild( d.createTextNode( "Αναίρεση διαγραφής" ) );
				
				undo.appendChild( undoLink );
				
				comment.parentNode.insertBefore( undo, comment.nextSibling );
				
				if( daddy != 0 ) {
					Comments.showDeleteButton( daddy, true );
				}	
				<?php
				break;
			case 2: // Already deleted
				?>alert( 'Το σχόλιο αυτό έχει ήδη διαγραφεί' );<?php
				break;
			case 3: // Has children-comments
				?>alert( 'Δεν μπορείς να διαγράψεις αυτό το σχόλιο' );<?php
				break;
			case 4: // Not an admin or not the creator
				?>alert( 'Δεν έχεις τα κατάλληλα δικαιώματα για να διαγράψεις αυτό το σχόλιο' );<?php
				break;
			case 5: // Too old comment
				?>alert( 'Το σχόλιο αυτό είναι αρκετά παλιό για να διαγραφεί' );<?php
				break;
			case 6: // Some other error
			default:
				?>alert( 'Υπήρξε κάποιο πρόβλημα με τη διαγραφή του σχολίου. Παρακαλώ δοκίμασε ξανά αργότερα.' );<?php
				break;
		}
	}
*/
?>
