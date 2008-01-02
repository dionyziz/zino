<?php
	function UnitCommentsNew( tString $text, tInteger $parent, tInteger $compage, tInteger $type, tInteger $indent, tCoalaPointer $callback ) {
		global $libs;
		global $user;
		global $xc_settings;
		
		$libs->Load( 'comment' );
		
		$text = $text->Get();
		
		if ( $user->IsAnonymous() && !$xc_settings[ 'anonymouscomments' ] ) {
            ?>alert('Παρακαλώ ξανακάνε είσοδο στο ' + <?php
            echo json_encode( $rabbit_settings[ 'applicationname' ] );
            ?> + ' για να πραγματοποιήσεις το σχόλιό σου');window.location.reload();<?php
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
	}
?>
