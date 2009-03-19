<?php
    function UnitFrontpageImageNew( Image $image ) {
        ?>alert( 'image id: <?php
        echo $image->Id;
        ?>, userid: <?php
        echo $image->User->Id;
        ?>: <?php
        echo $image->User->Name;
        ?>' );<?php
    }
?>
