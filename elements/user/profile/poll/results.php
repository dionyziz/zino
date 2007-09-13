<?php

    function ElementUserProfilePollResults( $poll ) {
        ?><div class="pollresults">
            <h4><?php
            echo htmlspecialchars( $poll->Question );
            ?></h4>
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
