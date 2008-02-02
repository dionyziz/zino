<?php
    function ElementDeveloperTestRun( RunResult $runresult ) {
        ?><li><?php
        echo $runresult->RunName;
        if ( $runresult->Success ) {
            ?>: <span class="pass">PASS</span> <span class="subject">(<?php
            echo $runresult->NumAssertions;
            ?> assertion<?php
            if ( $runresult->NumAssertions != 1 ) {
                ?>s<?php
            }
            ?>)</span><?php
        }
        else {
            ?>: <?php
            if ( $runresult instanceof FailedRunResult ) {
                ?><span class="fail">UNANTICIPATED FAIL</span><br />
                <strong>Unhandled Exception: </strong><br /><?php
                echo htmlspecialchars( $runresult->ExceptionMessage );
                ?><br /><?php
            }
            else {
                ?><span class="fail">FAIL</span> <span class="subject">(<?php
                echo $runresult->NumSuccessfulAssertions;
                ?> out of <?php
                echo $runresult->NumAssertions;
                ?> assertion<?php
                if ( $runresult->NumAssertions != 1 ) {
                    ?>s<?php
                }
                ?> pass)</span><br /><ul class="assertresults"><?php
                foreach ( $runresult as $assertresult ) {
                    if ( !$assertresult->Success ) {
                        ?><li><b>Assertion failed:</b> <em class="message"><?php
                        echo htmlspecialchars( $assertresult->Message );
                        ?></em><br />
                        <dl>
                            <dt>Expected</dt>
                            <dd class="expected"><?php
                            ob_start();
                            var_dump( $assertresult->Expected );
                            echo nl2br( htmlspecialchars( ob_get_clean() ) );
                            ?></dd>
                            <dt>Actual</dt>
                            <dd class="actual"><?php
                            ob_start();
                            var_dump( $assertresult->Actual );
                            echo nl2br( htmlspecialchars( ob_get_clean() ) );
                            ?></dd>
                        </dl>
                        </li><?php
                    }
                }
                ?></ul><?php
            }
        }
        ?></li><?php
    }
?>
