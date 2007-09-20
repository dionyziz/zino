<?php

    function ElementUserProfilePollResults( $poll, $theuser ) {
        global $xc_settings;
        global $user;

        ?><div class="pollresults" id="userpoll_<?php
            echo $poll->Id;
            ?>">
            <h4><?php
                if ( $user->Id() == $theuser->Id() ) {
                    ?><a style="float:right;" title="διαγραφή δημοσκόπησης" onclick="Poll.DeletePoll( <?php
                    echo $poll->Id;
                    ?> );"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/delete.png" alt="διαγραφή δημοσκόπησης" /></a><?php
                }

                echo htmlspecialchars( $poll->Question );

                if ( $user->Id() == $theuser->Id() ) {
                    ?> <a onclick="Poll.EditQuestion( <?php
                        echo $poll->Id;
                    ?>, '<?php
                        echo $poll->Question;
                    ?>' );" title="επεξεργασία τίτλου"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/edit.png" alt="επεξεργασία τίτλου" /></a><?php
                }

            ?></h4>

            <ul class="results"><?php

                $options = $poll->Options;
                foreach ( $options as $option ) {
                    ?><li><dl>
                        <dt><?php
                        echo htmlspecialchars( $option->Text );
                        ?><div id="optiontoolbox_<?php
                        echo $option->Id;
                        ?>" class="optiontoolbox"><?php

                            if ( $user->Id() == $theuser->Id() ) {
                                ?> <a onclick="Poll.EditOption( <?php
                                    echo $option->Id;
                                ?>, '<?php
                                    echo $option->Text;
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
                
            ?></ul>
        </div><?php
    }

?>
