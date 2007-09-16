<?php

    function ElementUserProfilePollView( $poll, $theuser ) {
        global $xc_settings;
        global $user;

        ?><div id="userpoll_<?php
        echo $poll->Id;
        ?>" class="pollview">
            <h4><?php
                if ( $user->Id() == $theuser->Id() ) {
                    ?><a style="float:right;" onclick="Poll.DeletePoll( <?php
                    echo $poll->Id;
                    ?> );" alt="διαγραφή δημοσκόπησης" title="διαγραφή δημοσκόπησης"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/delete.png" alt="διαγραφή δημοσκόπησης" /></a><?php
                }

                echo htmlspecialchars( $poll->Question );

                ?> <a onclick="Poll.EditQuestion( <?php
                    echo $poll->Id;
                ?>, '<?php
                    echo $poll->Question;
                ?>' );"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/edit.png" alt="επεξεργασία τίτλου" /></a>
            </h4>

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
