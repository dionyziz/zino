<?php
    function ElementDeveloperTestCase( Testcase $testcase, array $testsran, array $testcaseresults, array $indexbyname ) {
        $name = $testcase->Name;
        ?><li><input type="checkbox" name="runtests[]" value="<?php
        echo htmlspecialchars( $name );
        ?>" id="rabbit_test_<?php
        echo htmlspecialchars( $name );
        ?>"<?php
        if ( isset( $testsran[ $name ] ) ) {
            ?> checked="checked"<?php
        }
        ?> /><label for="rabbit_test_<?php
        echo htmlspecialchars( $name );
        ?>"><?php
        echo htmlspecialchars( $name );
        ?></label><?php
        if ( isset( $testsran[ $name ] ) ) {
            $testresult = $testcaseresults[ $indexbyname[ $name ] ];
            if ( $testresult->Success ) {
                // testcase pass
                ?>: <span class="pass">PASS</span> <span class="subject">(<?php
                echo $testresult->NumAssertions;
                ?> assertion<?php
                if ( $testresult->NumAssertions != 1 ) {
                    ?>s<?php
                }
                ?> in <?php
                echo $testresult->NumRuns;
                ?> run<?php
                if ( $testresult->NumRuns != 1 ) {
                    ?>s<?php
                }
                ?>)</span><?php
            }
            else {
                ?>: <span class="fail">FAIL</span> <span class="subject">(<?php
                echo $testresult->NumSuccessfulRuns;
                ?> out of <?php
                echo $testresult->NumRuns;
                ?> run<?php
                if ( $testresult->NumRuns != 1 ) {
                    ?>s<?php
                }
                ?> pass)</span><br /><ol><?php
                foreach ( $testresult as $runresult ) {
                    Element( 'developer/test/run', $runresult );
                }
                ?></ol><?php
            }
        }
        ?></li><?php
    }
?>
