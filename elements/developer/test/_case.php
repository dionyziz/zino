<?php
    function ElementDeveloperTestCase( Testcase $testcase, array $testsran, array $testcaseresults, array $indexbyname ) {
        $name = $testcase->Name();
        ?><li><input type="checkbox" name="runtests[]" value="<?php
        echo htmlspecialchars( $name );
        ?>" id="rabbit_test_<?php
        echo $i;
        ?>"<?php
        if ( isset( $testsran[ $name ] ) ) {
            ?> checked="checked"<?php
        }
        ?> /><label for="rabbit_test_<?php
        echo $i;
        ?>"><?php
        echo htmlspecialchars( $name );
        ?></label><?php
        if ( isset( $testsran[ $name ] ) ) {
            $testresult = $testcaseresults[ $indexbyname[ $name ] ];
            if ( $testresult->Success() ) {
                // testcase pass
                ?>: <span class="pass">PASS</span> <span class="subject">(<?php
                echo $testresult->NumAssertions();
                ?> assertion<?php
                if ( $testresult->NumAssertions() != 1 ) {
                    ?>s<?php
                }
                ?> in <?php
                echo $testresult->NumRuns();
                ?> run<?php
                if ( $testresult->NumRuns() != 1 ) {
                    ?>s<?php
                }
                ?>)</span><?php
            }
            else {
                ?>: <span class="fail">FAIL</span> <span class="subject">(<?php
                echo $testresult->NumSuccessfulRuns();
                ?> out of <?php
                echo $testresult->NumRuns();
                ?> run<?php
                if ( $testresult->NumRuns() != 1 ) {
                    ?>s<?php
                }
                ?> pass)</span><br /><ol><?php
                foreach ( $testresult as $runresults ) {
                    ?><li><?php
                    echo $runresults->RunName();
                    if ( $runresults->Success() ) {
                        ?>: <span class="pass">PASS</span> <span class="subject">(<?php
                        echo $runresults->NumAssertions();
                        ?> assertion<?php
                        if ( $runresults->NumAssertions() != 1 ) {
                            ?>s<?php
                        }
                        ?>)</span><?php
                    }
                    else {
                        ?>: <span class="fail">FAIL</span> <span class="subject">(<?php
                        echo $runresults->NumSuccessfulAssertions();
                        ?> out of <?php
                        echo $runresults->NumAssertions();
                        ?> assertion<?php
                        if ( $runresults->NumAssertions() != 1 ) {
                            ?>s<?php
                        }
                        ?> pass)</span><br /><ul class="assertresults"><?php
                        foreach ( $runresults as $assertresult ) {
                            if ( !$assertresult->Success() ) {
                                ?><li><b>Assertion failed:</b> <em class="message"><?php
                                echo $assertresult->Message();
                                ?></em><br />
                                <dl>
                                    <dt>Expected</dt>
                                    <dd class="expected"><?php
                                    ob_start();
                                    var_dump( $assertresult->Expected() );
                                    echo htmlspecialchars( ob_get_clean() );
                                    ?></dd>
                                    <dt>Actual</dt>
                                    <dd class="actual"><?php
                                    ob_start();
                                    var_dump( $assertresult->Actual() );
                                    echo htmlspecialchars( ob_get_clean() );
                                    ?></dd>
                                </dl>
                                </li><?php
                            }
                        }
                        ?></ul><?php
                    }
                    ?></li><?php
                }
                ?></ol><?php
            }
        }
        ?></li><?php
    }
?>
