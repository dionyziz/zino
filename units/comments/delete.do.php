<?php
	function UnitCommentsDelete( tInteger $commentid ) {
		global $user;
		global $libs;
		
        $commentid = $commentid->Get();
        
		$libs->Load( 'comment' );
		
		$comment = New Comment( $commentid );
		$change = $comment->Kill();
		switch( $change ) {
			case 1: // OK
				?>id = <?php echo $commentid; ?>;
				comment = document.getElementById( 'comment_loading_delete_' + id );
				comment.style.display = "none";
				
				undo = d.createElement( "div" );
				undo.id = 'comment_undo_delete_' + id;
				undo.style.width = '100%';
				undo.style.textAlign = 'center';
				undo.style.paddingBottom = '5px';
				undo.style.cursor = 'pointer';
				undo.appendChild( d.createTextNode( "Το σχόλιο διεγράφη. " ) );
				
				undoLink = d.createElement( "a" );
				undoLink.onclick = ( function( id ) { return function() { Comments.UndoDelete( id ); return false; } } )( id );
				undoLink.appendChild( d.createTextNode( "Αναίρεση διαγραφής" ) );
				
				undo.appendChild( undoLink );
				
				comment.parentNode.insertBefore( undo, comment.nextSibling );
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
?>
