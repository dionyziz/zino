<?php

    function ElementPollOptionToolbox( $option, $theuser ) {
        global $user;
        global $xc_settings;

        if ( $user->Id() != $theuser->Id() ) {
            return;
        }

        ?><div id="optiontoolbox_<?php
        echo $option->Id;
        ?>" class="optiontoolbox"><a onclick="Poll.EditOption( <?php
                echo $option->Id;
            ?>, <?php
                echo htmlspecialchars( w_json_encode( $option->Text ) );
            ?> );" title=''><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/edit.png" alt='' />
            </a><a style="margin-left: 1px;" title='' onclick="Poll.DeleteOption( <?php
            echo $option->Id;
            ?> );"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/delete.png" alt='' />
            </a>
        </div><?php
    }

?>
