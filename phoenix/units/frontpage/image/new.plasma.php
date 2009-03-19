<?php
    function UnitFrontpageImageNew( Image $image ) {
        ?>var existnode = $( 'div.plist ul li a span img[alt="<?php
        echo $image->User->Name;
        ?>"] ).parent().parent().parent()[0];
        if ( existnode ) {
            alert( 'removing same user photo' );
            $( existnode ).remove();
        }
        else {
            alert( 'removing last image' );
            $( 'div.plist ul li:last-child' ).remove();
        }
        <?php
        ?>alert( 'image id: <?php
        echo $image->Id;
        ?>, userid: <?php
        echo $image->User->Id;
        ?>: <?php
        echo $image->User->Name;
        ?>' );<?php
    }
?>
