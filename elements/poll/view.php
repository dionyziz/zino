<?php

    function ElementPollView( $poll, $theuser ) {
        global $xc_settings;
        global $user;

        $hasvoted = $poll->UserHasVoted( $theuser );
        
        if ( !$hasvoted ) {
            // vote div

            ?><div id="userpoll_<?php
            echo $poll->Id;
            ?>" class="pollview"><?php
        }
        else {
            // results div

            ?><div class="pollresults" id="userpoll_<?php
            echo $poll->Id;
            ?>"><?php
        }
            ?><h4><?php
                if ( $user->Id() == $theuser->Id() ) {
                    ?><a style="float:right;" onclick="Poll.DeletePoll( <?php
                    echo $poll->Id;
                    ?> );" alt="διαγραφή δημοσκόπησης" title="διαγραφή δημοσκόπησης"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/delete.png" alt="διαγραφή δημοσκόπησης" /></a><?php
                }

                echo htmlspecialchars( $poll->Question );

                if ( $user->Id() == $theuser->Id() ) {
                    ?> <a onclick="Poll.EditQuestion( <?php
                        echo $poll->Id;
                    ?>, '<?php
                        echo addslashes( $poll->Question );
                    ?>' );"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/edit.png" alt="επεξεργασία τίτλου" /></a><?php
                }
            ?></h4>

            <ul class="<?php
                if ( !$hasvoted ) {
                    ?>poll<?php
                }
                else {
                    ?>results<?php
                }
                ?>"><?php
                $options = $poll->Options;
                foreach ( $options as $option ) {
                    Element( "poll/option/view", $option, $theuser );
                }

                if ( $user->Id() == $theuser->Id() ) {
                    ?><li id="createpop_<?php
                    echo $poll->Id;
                    ?>"><a style="cursor: pointer; margin-left: 5px;" onclick="Poll.CreateOptionOnView( <?php
                    echo $poll->Id;
                    ?>, <?php
                    echo $hasvoted;
                    ?> ); return false;"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/page_new.gif" alt="Προσθήκη επιλογής" /></a>
                    </li><?php
                }

            ?></ul>
        </div><?php
    }

?>
