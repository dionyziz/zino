<?php

    function ElementPollOptionVote( $option, $poll, $theuser ) {
        global $user;
        global $xc_settings;

        ?><li><dl>
            <dt id="polloption_<?php
            echo $option->Id;
            ?>" onmouseover="g( 'optiontoolbox_<?php
            echo $option->Id;
            ?>' ).style.visibility='visible';" onmouseout="g( 'optiontoolbox_<?php
            echo $option->Id;
            ?>' ).style.visibility='hidden';">
            <input type="radio" id="p_<?php
            echo $poll->Id;
            ?>_<?php
            echo $option->Id;
            ?>" name="option" value="0" onclick="Poll.Vote( <?php
            echo $poll->Id;
            ?>, <?php
            echo $option->Id;
            ?> );" /><label style="margin-left: 2px;" for="p_<?php
            echo $poll->Id;
            ?>_<?php
            echo $option->Id;
            ?>"><?php
            echo htmlspecialchars( $option->Text );
            ?></label><?php

            Element( "poll/option/toolbox", $option, $theuser );

            ?></dt><dd></dd>
        </dl></li><?php
    }

?>
