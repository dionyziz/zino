<?php

    function ElementPollOptionVote( $option, $poll, $theuser ) {
        global $user;
        global $xc_settings;

        ?><li><dl>
            <dt><input type="radio" id="p_<?php
            echo $poll->Id;
            ?>_<?php
            echo $option->Id;
            ?>" name="option" value="0" onclick="Poll.Vote( <?php
            echo $poll->Id;
            ?>, <?php
            echo $option->Id;
            ?> );" /></dt>
            <dd><label for="p_<?php
            echo $poll->Id;
            ?>_<?php
            echo $option->Id;
            ?>"><?php
            echo htmlspecialchars( $option->Text );
            ?></label></dd>
        </dl></li><?php
    }

?>
