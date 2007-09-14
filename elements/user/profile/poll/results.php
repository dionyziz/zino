<?php

    function ElementUserProfilePollResults( $poll ) {
        global $xc_settings;

        ?><div class="pollresults">
            <h4><a style="float:right;" onclick="Poll.Delete( <?php
            echo $poll->Id;
            ?> );"><img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>icons/delete.png" alt="διαγραφή δημοσκόπησης" /></a><?php
            echo htmlspecialchars( $poll->Question );
            ?><a><img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>icons/edit.png" alt="επεξεργασία τίτλου" /></a></h4>
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
