<?php

    function UnitCommentsEdit( tString $text, tInteger $eid, tCoalaPointer $callback ) {
        global $libs;

        $libs->Load( 'comment' );
        
        $text = $text->Get();
        $eid = $eid->Get();

		if( $text != '' ) {
			if( $eid != 0 ) {
				$comment = New Comment ( $eid );
				$comment->Update( $text );
			}
		}

		$formatted = mformatcomments( array( $text ) );
		
		echo $callback;
		?>(<?php
		echo $eid;
		?>,<?php
		echo w_json_encode( $formatted[ 0 ] );
		?>,<?php
		echo w_json_encode( $comment->User()->Signature() );
		?>);<?php
	}
	
?>
