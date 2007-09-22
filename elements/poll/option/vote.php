<?php

    function ElementPollOptionVote( $option, $theuser ) {
        global $user;
        global $xc_settings;

        $poll = $option->Poll;

        ?><li><dl>
            <dt id="polloption_<?php
            echo $option->Id;
            ?>"><input type="radio" id="p_<?php
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
