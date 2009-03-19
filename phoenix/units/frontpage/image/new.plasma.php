<?php
    function UnitFrontpageImageNew( Image $image ) {
        ?>var newli = document.createElement( 'li' );
        var newlink = document.createElement( 'a' );
        $( newlink ).attr( "href" , "?p=photo&id=<?php
        echo $image->Id;
        ?>" );
        $( newlink ).html( <?php
        ob_start();
        Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , $image->Numcomments );
        echo w_json_encode( ob_get_clean() );
        ?> );
        $( newli ).append( newlink ).hide();
        var existnode = $( 'div.plist ul li a span img[alt="<?php
        echo $image->User->Name;
        ?>"]' ).parent().parent().parent();
        if ( existnode ) {
            //alert( 'removing same user photo' );
            $( existnode ).animate( {
                    width: "0"
                } , 400 , function() {
                    $( this ).remove();
                } );
        }
        else {
            //alert( 'removing last image' );
            $( 'div.plist ul li:last-child' ).animate( {
                    width: "0"
                } , 400 , function() {
                    $( this ).remove();
                } );
        }
        $( 'div.plist ul' ).prepend( newli );
        $( newli ).animate( {
                width: "102px"
        } , 400 );
        <?php
        /*        
        ?>alert( 'image id: <?php
        echo $image->Id;
        ?>, userid: <?php
        echo $image->User->Id;
        ?>: <?php
        echo $image->User->Name;
        ?>' );<?php
        */
    }
?>
