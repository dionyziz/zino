<?php

    function ElementUserProfilePollView( $poll, $theuser ) {
        global $page;

        $page->AttachScript( "js/poll.js" );

        ?><div class="userpoll">
            <h4><?php
                echo htmlspecialchars( $poll->Question );
            ?></h4>

            <ul class="poll"><?php
                $options = $poll->Options;
                foreach ( $options as $option ) {
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

            ?></ul>
        </div><?php
    }

?>
