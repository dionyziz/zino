<?php
    function UnitUserProfileEasyuploadadd( $imageid ) {
        global $libs;
        
        $libs->Load( 'image/image' );
        $image = New Image( $imageid->Get() );
        ?>var newli = document.createElement( 'li' );
        var newlink = document.createElement( 'a' );
        $( newlink ).attr( 'href' , '?p=photo&id=<?php
        echo $imageid;
        ?>' ).html( <?php
        ob_start();
        Element( 'image/view' , $imageid , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , 0 );
        ?> );
        $( 'div#profile div.main div.photos ul.plist li.addphoto' ).after( newli );<?php
    }
?>
