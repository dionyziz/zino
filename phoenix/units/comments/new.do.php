<?php
    function UnitCommentsNew( tText $text, tInteger $parent, tInteger $compage, tInteger $type, tCoalaPointer $node, tCoalaPointer $callback ) {
        global $libs;
        global $user;
        
        $libs->Load( 'comment' );
        $libs->Load( 'wysiwyg' );
        
        $text = $text->Get();
        $text = trim( $text );
        
        if ( $text == 'Was George Orwell right about 1984?' ) {
            ?>var easterEggImg = document.createElement( 'img' );
            easterEggImg.src = 'http://images.zino.gr/media/58/72787/72787_full.jpg';
            var theDiv = document.createElement( 'div' );
            theDiv.appendChild( easterEggImg );
            theDiv.appendChild( document.createTextNode( 'With love from the Zino Team' ) );
            easterEggImg.style.display = 'block';
            easterEggImg.style.margin = '10px auto 10px auto';
            easterEggImg.style.cursor = 'pointer';
            easterEggImg.onclick = function () {
                Modals.Destroy();
            }
            Modals.Create( theDiv, 800, 600 );<?php
            return;
        }
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
        $text = nl2br( htmlspecialchars( $text ) );
        $text = WYSIWYG_PostProcess( $text );
        die( '~'.$text );
        $comment->Text = $text;
        $comment->Userid = $user->Id;
        $comment->Parentid = $parent;
        $comment->Typeid = $type;
        $comment->Itemid = $compage;
        $comment->Save();

        Element::ClearFromCache( 'comment/list', $type, $compage );
        
        echo $callback;
        ?>( <?php
        echo $node;
        ?>, <?php
        echo $comment->Id;
        ?>, <?php
        echo $parent;
        ?>, <?php
        echo w_json_encode( nl2br( $comment->Text ) );
        ?> );<?php
    }
?>
