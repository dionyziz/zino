<?php
    function ElementWYSIWYGView( $id = 'wysiwyg', $contents = '' ) {
        global $user;

        ?><div id="<?php
        echo $id;
        ?>" class="wysiwyg"><?php
        echo $contents;
        ?></div><?php
    }
?>
