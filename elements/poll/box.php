<?php

    function ElementPollBox( $poll, $theuser = false ) {
        global $xc_settings;
        global $user;

        if ( $theuser === false ) {
            $theuser = $poll->User;
        }

        $hasvoted = $poll->UserHasVoted( $user );
        
        if ( !$hasvoted ) {
            // vote div

            ?><div id="userpoll_<?php
            echo $poll->Id;
            ?>" class="pollbox pollview"><?php
        }
        else {
            // results div

            ?><div class="pollbox pollresults" id="userpoll_<?php
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
                        echo htmlspecialchars( w_json_encode( $poll->Question ) );
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
                    Element( "poll/option/view", $option, $poll, $theuser );
                }

                ?><li style="float: right;" id="pollcommentslink_<?php
                echo $poll->Id;
                ?>"><a href="?p=poll&amp;id=<?php
                echo $poll->Id;
                ?>"><?php
                echo $poll->NumComments
                ?> σχόλι<?php
                if ( $poll->NumComments == 1 ) {
                    ?>ο<?php
                }
                else {
                    ?>α<?php
                }
                ?></a></li>
                <li id="createpop_<?php
                echo $poll->Id;
                ?>" <?php
                if ( $user->Id() != $theuser->Id() ) {
                    ?>style="visibility: hidden;"<?php
                }
                ?>><a style="cursor: pointer; margin-left: 5px;" onclick="Poll.CreateOptionOnView( <?php
                echo $poll->Id;
                ?>, <?php
                echo $hasvoted ? "true" : "false";
                ?> ); return false;"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/page_new.gif" alt="Προσθήκη επιλογής" /></a>
                </li><?php

            ?></ul>
        </div><?php
    }

?>
