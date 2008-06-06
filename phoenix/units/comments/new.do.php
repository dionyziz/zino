<?php
	function UnitCommentsNew( tString $text, tInteger $parent, tInteger $compage, tInteger $type, tInteger $indent, tCoalaPointer $callback ) {
		global $libs;
		global $user;
		
		$libs->Load( 'comment' );
		
		$text = $text->Get();
		
		if ( !$user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
			?>alert( "Δεν έχεις το δικαίωμα να δημιουργήσεις νέο σχόλιο. Παρακαλώ κάνε login" );<?php
			return;
		}
		
		if ( $text == '' ) {
			?>alert( "Δεν μπορείς να δημιουργήσεις κενό σχόλιο" );<?php
			return;
		}
		
		$parent = $parent->Get();
		$compage = $compage->Get();
		$type = $type->Get();
		
		$comment = New Comment();
		$comment->Text = $text;
		$comment->Userid = $user->Id;
		$comment->Typeid = $type;
		$comment->Itemid = $compage;
		$comment->Save();
		
		echo $callback;
		?>();<?php
		/*
		if ( $user->IsAnonymous() && !$xc_settings[ 'anonymouscomments' ] ) {
            ?>alert('Ðáñáêáëþ îáíáêÜíå åßóïäï óôï ' + <?php
            echo json_encode( $rabbit_settings[ 'applicationname' ] );
            ?> + ' ãéá íá ðñáãìáôïðïéÞóåéò ôï ó÷üëéü óïõ');window.location.reload();<?php
            return;
		}
		
		if ( $text == '' ) {
			return;
		}
		
		$parent = $parent->Get();
		$compage = $compage->Get();
		$type = $type->Get();
		
		$id = MakeComment( $text, $parent, $compage, $type );
		$comment = New Comment( $id );
		
		ob_start();
		Element( 'comment/view', $comment, $indent->Get() );
		$newcomm = preg_replace('/&nbsp;+/', ' ', ob_get_clean()); // Prevent the &nbsp; cause an undefined entity error
		
		if( $indent->Get() != 0 ) {
			?>Comments.hideDeleteButton( <?php
			echo $parent;
			?>, true );<?php
		}
		
		echo $callback;
		?>( <?php
		echo w_json_encode( $newcomm );
		?>,<?php
		echo $parent;
		?>,<?php
		echo $type;
		?>);<?php
	*/
		
	}
?>
