<?php

    function ElementUserProfilePollResults( $poll ) {
        ?><div class="pollresults">
            <h4><a style="float:right;"><img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>/images/icons/delete.png" style="width: 16px; height: 16px;" alt="delete poll" /></a><?php
            echo htmlspecialchars( $poll->Question );
            ?><a><img src="<?php
            echo $xc_settings[ 'staticimagesurl' ];
            ?>/images/icons/edit.png" style="width: 16px; height: 16px;" alt="edit poll title" /></a></h4>
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
