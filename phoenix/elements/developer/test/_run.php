<?php
    class ElementDeveloperTestRun extends Element {
        public function Render( RunResult $runresult ) {
            global $water;

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
                    if ( $assertresult instanceof AssertResultFailedByException ) {
                        ?><li><b>Unanticipated fail:</b> <em class="message"><?php
                        echo htmlspecialchars( $assertresult->Message );
                        ?></em><br /><?php
                        $water->callstack( $water->callstack_lastword( $assertresult->Callstack ) );
                        ?></li><?php
                        break;
                    }

                    if ( !$assertresult->Success ) {
                        ?><li><b>Assertion failed:</b> <em class="message"><?php
                        echo htmlspecialchars( $assertresult->Message );
                        ?></em><br />
                        <dl>
                            <dt>Expected</dt>
                            <dd class="expected"><?php
                            ob_start();
                            Test_VarDump( $assertresult->Expected );
                            echo nl2br( htmlspecialchars( ob_get_clean() ) );
                            ?></dd>
                            <dt>Actual</dt>
                            <dd class="actual"><?php
                            ob_start();
                            Test_VarDump( $assertresult->Actual );
                            echo nl2br( htmlspecialchars( ob_get_clean() ) );
                            ?></dd>
                        </dl>
                        </li><?php
                    }
                }
                ?></ul><?php
            }
            ?></li><?php
        }
    }
?>
