<?php
    function UnitShoutboxNew( tText $text , tCoalaPointer $node ) {
        global $user;
        global $libs;
        
        $libs->Load( 'wysiwyg' );
        $libs->Load( 'shoutbox' );
        $libs->Load( 'comet' );
        
        $text = $text->Get();
        if ( !$user->Exists() ) {
            ?>alert( "Πρέπει να είσαι συνδεδεμένος για να συμμετέχεις στην συζήτηση" );
            window.location.reload();<?php
            return;
        }
        
        if ( trim ( $text ) == '' ) {
            ?>alert( "Δεν μπορείς να δημοσιεύσεις κενό μήνυμα" );
            window.location.reload();<?php
            return;
        }
        
        $shout = New Shout();
        $shout->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) ); // TODO: WYSIWYG
        $shout->Save();
        
        ?>var toolbox = document.createElement( 'div' );
        var deletelink = document.createElement( 'a' );
        $( deletelink ).attr( 'href' , '' )
        .css( 'padding-left' , '16px' )
        .click( function() {
            return Frontpage.DeleteShout( '<?php
                echo $shout->Id;
            ?>' );
        } );
        $( toolbox ).addClass( 'toolbox' ).append( deletelink );
        var node = <?php
        echo $node;
        ?>;
        $( node )
        .prepend( toolbox )
        .attr( {
            id : "s_<?php
            echo $shout->Id;
            ?>" } );
        var text = <?php
            echo w_json_encode( $shout->Text );
        ?>;

        if ( $.browser.msie ) {
            $( node ).find( 'div.text' ).html( text );
        }
        else {
            $( node ).find( 'div.text' ).html( text.replace( /&nbsp;/g, ' ' ) );
        }
        
        Frontpage.Shoutbox.OnMyMessagePosted( <?php
        echo $shout->Id;
        ?> );<?php
        
        Comet_Publish( 'shoutbox', $shout->Id, $shout->Text, array(
            'id' => $shout->User->Id, 
            'name' => $shout->User->Name,
            'avatar' => $shout->User->Avatarid,
            'subdomain' => $shout->User->Subdomain
        ) );
    }
?>
