<?php
    function ElementDeveloperTest( tStringArray $runtests ) {
        global $libs;
        global $page;
        global $water;
        
        $libs->Load( 'rabbit/unittest' );
        $page->SetTitle( 'Unit Test' );
        $page->AttachStylesheet( 'css/rabbit/unittest.css' );
        
        $validtests = Test_GetTestcases();
        $validtestsbyname = array();
        foreach ( $validtests as $testcase ) {
            $validtestsbyname[ $testcase->Name() ] = $testcase;
        }
        
        $tester = New Tester();
        $runtestsbyname = array();
        $indexbyname = array();
        $i = 0;
        foreach ( $runtests as $testname ) {
            $testname = $testname->Get();
            $runtestsbyname[ $testname ] = true;
            if ( !isset( $validtestsbyname[ $testname ] ) ) {
                $water->Warning( 'Testname "' . $testname . '" which you tried to run is not a valid testname' );
            }
            else {
                $tester->AddTestcase( $validtestsbyname[ $testname ] );
                $indexbyname[ $testname ] = $i;
                ++$i;
            }
        }
        $tester->Run();
        $testcaseresults = $tester->GetResults();
        
        ?>
        <form action="" method="get" class="unittest">
        <br /><br />Select which test or tests to run:<br />
        <input type="hidden" name="p" value="unittest" />
        <ul class="testcases"><?php
        foreach ( $validtests as $i => $testcase ) {
            $name = $testcase->Name();
            ?><li><input type="checkbox" name="runtests[]" value="<?php
            echo htmlspecialchars( $name );
            ?>" id="rabbit_test_<?php
            echo $i;
            ?>"<?php
            if ( isset( $runtestsbyname[ $name ] ) ) {
                ?> checked="checked"<?php
            }
            ?> /><label for="rabbit_test_<?php
            echo $i;
            ?>"><?php
            echo htmlspecialchars( $name );
            ?></label><?php
            if ( isset( $runtestsbyname[ $name ] ) ) {
                $testresult = $testcaseresults[ $indexbyname[ $name ] ];
                if ( $testresult->Success() ) {
                    // testcase pass
                    ?>: <span class="fail">PASS</span> <span class="subject">(<?php
                    echo $testresult->NumAssertions();
                    ?> assertions in <?php
                    echo $testresult->NumRuns();
                    ?> runs)</span><?php
                }
                else {
                    ?>: <span class="fail">FAIL</span> <span class="subject">(<?php
                    echo $testresult->NumSuccessfulRuns();
                    ?> out of <?php
                    echo $testresult->NumRuns();
                    ?> runs pass)</span><br /><ol><?php
                    foreach ( $testresult as $runresults ) {
                        ?><li><?php
                        echo $runresults->RunName();
                        if ( $runresults->Success() ) {
                            ?>: <span class="pass">PASS</span> <span class="subject">(<?php
                            echo $runresults->NumAssertions();
                            ?> assertions)</span><?php
                        }
                        else {
                            ?>: <span class="fail">FAIL</span><br /><ul class="assertresults"><?php
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
        ?></ul><br />
        <input type="submit" value="Test!" />
        </form><?php
    }
?>
