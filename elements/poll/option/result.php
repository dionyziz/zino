<?php
    
    function ElementPollOptionResult( $option, $theuser ) {
        global $user;
        global $xc_settings;

        ?><li><dl>
            <dt id="polloption_<?php
            echo $option->Id;
            ?>" onmouseover="g( 'optiontoolbox_<?php
            echo $option->Id;
            ?>' ).style.visibility='visible';" onmouseout="g( 'optiontoolbox_<?php
            echo $option->Id;
            ?>' ).style.visibility='hidden';"><?php
            echo htmlspecialchars( $option->Text );
            ?><div id="optiontoolbox_<?php
            echo $option->Id;
            ?>" class="optiontoolbox"><?php

                if ( $user->Id() == $theuser->Id() ) {
                    ?> <a onclick="Poll.EditOption( this, <?php
                        echo $option->Id;
                    ?>, '<?php
                        echo addslashes( $option->Text );
                    ?>' );" title="επεξεργασία επιλογής"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/edit.png" alt="επεξεργασία επιλογής" /></a><a style="margin-left: 2px;" title="διαγραφή επιλογής" onclick="Poll.DeleteOption( <?php
                    echo $option->Id;
                    ?> );"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/delete.png" alt="διαγραφή επιλογής" /></a><?php
                }

            ?></div></dt>
            <dd>
                <div class="polloption">
                    <div class="pollanswer" style="width:<?php
                    echo $option->Percentage;
                    ?>%"></div>
                </div>
            </dd>
        </dl></li><?php
    }

?>
