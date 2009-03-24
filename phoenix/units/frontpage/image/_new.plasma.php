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
        usernameact = "<?php
        echo $image->User->Name;
        ?>";
        var existnode = $( 'div.plist ul li a span img[alt="<?php
        echo $image->User->Name;
        ?>"]' ).parent().parent().parent();
        if ( existnode && $( 'div.plist ul li a span img:first' ).attr('alt') == usernameact ){
            $( newli ).append( newlink ).css( "position" , "absolute" ).hide();
            $( 'div.plist ul' ).prepend( newli );
            $( newli ).show().fadeIn( 800,function (){
                $( this ).css( "position", "relative" );
            } );
            $( existnode ).fadeOut( 800, function(){
                $( this ).remove();
            } );
        }
        else{
            $( newli ).append( newlink ).css( "width" , "0" ).hide();

            $( 'div.plist ul' ).prepend( newli );
            $( newli ).animate( {
                width: "102px"
            } , 800 , 'linear' );
            if ( existnode ) {
                $( existnode ).animate( {
                        width: "0",
                        opacity: "0"
                    } , 500 , 'linear' , function() {
                        $( this ).remove();
                } );
            }
        }<?php
    }
?>
