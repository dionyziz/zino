<?php
    function ElementDeveloperTest( tStringArray $runtests ) {
        global $libs;
        global $page;
        
        $libs->Load( 'rabbit/unittest' );
        $page->SetTitle( 'Unit Test' );

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
            }
        }
        $tester->Run();
        $testcaseresults = $tester->GetResults();
        
        ?><br />Select which test or tests to run:<br />
        <form action="" method="get">
        <ul><?php
        foreach ( $validtests as $i => $testcase ) {
            $name = $testcase->Name();
            ?><li><input type="checkbox" name="runtests" value="<?php
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
                $testcaseresults = $testcaseresults[ $indexbyname[ $name ] ];
                if ( $testcaseresults->Success() ) {
                    // testcase pass
                    ?>: <span style="color:#509060">PASS</span> (<?php
                    echo $testcaseresults->NumAssertions();
                    ?> assertions in <?php
                    echo $testcaseresults->NumRuns();
                    ?> runs)<?php
                }
                else {
                    ?>: <span style="color:#906050">FAIL</span><br /><ol><?php
                    foreach ( $testcaseresults[ $indexbyname[ $name ] ] as $runresults ) {
                        ?><li><?php
                        echo $runresults->RunName();
                        if ( $runresults->Success() ) {
                            ?>: <span class="color:#509060">PASS</span> (<?php
                            echo $runresults->NumAssertions();
                            ?> assertions)<?php
                        }
                        else {
                            ?>: <span class="color:#906050">FAIL</span><br /><ul><?php
                            foreach ( $runresults as $assertresult ) {
                                if ( !$assertresult->Success() ) {
                                    ?><ol><b>Assertion failed:</b> <?php
                                    echo $assertresult->Message();
                                    ?><br />
                                    <dl>
                                        <dt>Expected</dt>
                                        <dd><?php
                                        ob_start();
                                        var_dump( $assertresult->Expected() );
                                        echo htmlspecialchars( ob_get_clean() );
                                        ?></dd>
                                        <dt>Actual</dt>
                                        <dd><?php
                                        ob_start();
                                        var_dump( $assertresult->Actual() );
                                        echo htmlspecialchars( ob_get_clean() );
                                        ?></dd>
                                    </dl>
                                    </ol><?php
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
