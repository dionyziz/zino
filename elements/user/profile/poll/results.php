<?php

    function ElementUserProfilePollResults( $poll, $theuser ) {
        global $xc_settings;
        global $user;

        ?><div class="pollresults" id="userpoll_<?php
            echo $poll->Id;
            ?>">
            <h4><?php
                if ( $user->Id() == $theuser->Id() ) {
                    ?><a style="float:right;" alt="διαγραφή δημοσκόπησης" title="διαγραφή δημοσκόπησης" onclick="Poll.DeletePoll( <?php
                    echo $poll->Id;
                    ?> );"><img src="<?php
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

            <ul class="results"><?php

                $options = $poll->Options;
                foreach ( $options as $option ) {
                    ?><li><dl>
                        <dt><?php
                        echo htmlspecialchars( $option->Text );
                        ?></dt>
                        <dd>
                            <div class="polloption">
                                <div class="pollanswer" style="width:<?php
                                echo $option->Percentage;
                                ?>%"></div>
                            </div>
                        </dd>
                    </dl></li><?php
                }
                
            ?></ul>
        </div><?php
    }

?>
