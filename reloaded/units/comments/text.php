<?php
    function UnitCommentsText( tInteger $commentid , tCoalaPointer $callback ) {
        global $libs;
        global $user;
        
        $commentid = $commentid->Get();
        
        $libs->Load( 'comment' );
        $comment = New Comment( $commentid );
        
        echo $callback;
        ?>(<?php
        echo $commentid;
        ?>, <?php
        echo w_json_encode( $comment->TextRaw() );
        ?>);<?php
    }
    
?>
