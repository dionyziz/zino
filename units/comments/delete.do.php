<?php
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
					var numcom = g( daddy + "_children" ).firstChild;
					var num = parseInt( numcom.nodeValue );
					--num;
					numcom.nodeValue = num;
					
					if( num == 0 ) {
						var lili = d.createElement( 'li' );
						var link = d.createElement( 'a' );
						link.style.cursor = "pointer";
						link.onclick = function() {
								Comments.Delete( daddy );
								return false;
							};
						
						link.appendChild( d.createTextNode( "Διαγραφή" ) );
						lili.appendChild( link );
						
						var toolbar = g( 'comment_' + daddy + '_toolbar' );
						if( toolbar.childNodes.length == 3 ) {
							toolbar.insertBefore( lili, toolbar.childNodes[2] );
						}
						else {
							toolbar.appendChild( lili );
						}
					}
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
?>
