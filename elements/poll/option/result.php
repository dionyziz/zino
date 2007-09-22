<?php
    
    function ElementPollOptionResult( $option, $theuser ) {
        global $user;
        global $xc_settings;

        ?><li><dl>
            <dt id="polloption_<?php
            echo $option->Id;
            ?>"><?php
            echo htmlspecialchars( $option->Text );
            
            Element( "poll/option/toolbox", $option, $theuser );

            ?></dt><dd>
                <div class="polloption">
                    <div class="pollanswer" style="width:<?php
                    echo $option->Percentage;
                    ?>%"></div>
                </div>
            </dd>
        </dl></li><?php
    }

?>
