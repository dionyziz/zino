#!/usr/bin/php
<?php

    $runtests = array();

    array_shift( $argv );

    foreach ( $argv as $i => $value ) {
        switch ( $value ) {
            default:
                $runtests[] = $value;
        }
    }

    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }
    
    global $settings;
    $settings = include 'settings.php';

    include 'models/water.php';
    include 'models/unittest.php';
    include 'models/db.php';

    $validTests = Test_GetTestcases();
    $validTestsByName = array();
    foreach ( $validTests as $testcase ) {
        $validTestsByName[ $testcase->Name ] = $testcase;
    }
    $tester = New Tester();
    $testsRan = array();
    $indexByName = array();
    $i = 0;

    foreach ( $runtests as $testname ) {
        if ( !isset( $validTestsByName[ $testname ] ) ) {
            // $water->Warning( 'Testname "' . $testname . '" which you tried to run is not a valid testname' );
            echo "Testname \"" . $testname . "\" which you tried to run is not a valid testname\n";
        }
        else {
            $tester->AddTestcase( $validTestsByName[ $testname ] );
            $indexByName[ $testname ] = $i;
            $testsRan[ $testname ] = true;
            ++$i;
        }
    }

    $tester->Run();
    $testcaseResults = $tester->GetResults();

    foreach ( $validTests as $i => $testcase ) {
        $name = $testcase->Name;
        if ( !isset( $testsRan[ $name ] ) ) {
            continue;
        }
        echo $name;
        ?>: <?php
        $testresult = $testcaseResults[ $indexByName[ $name ] ];
        if ( $testresult->Success ) {
            // testcase pass
            ?>PASS (<?php
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
            echo ") \n";
            continue;
        }
        ?>FAIL (<?php
        echo $testresult->NumSuccessfulRuns;
        ?> out of <?php
        echo $testresult->NumRuns;
        ?> run<?php
        if ( $testresult->NumRuns != 1 ) {
            ?>s<?php
        }
        ?> pass)<?php
        echo "\n";
        foreach ( $testresult as $runresult ) {
            echo $runresult->RunName;
            ?>: <?php
            if ( $runresult->Success ) {
                ?>PASS (<?php
                echo $runresult->NumAssertions;
                ?> assertion<?php
                if ( $runresult->NumAssertions != 1 ) {
                    ?>s<?php
                }
                ?>)<?php
                echo "\n";
                continue;
            }
            ?>FAIL (<?php
            echo $runresult->NumSuccessfulAssertions;
            ?> out of <?php
            echo $runresult->NumAssertions;
            ?> assertion<?php
            if ( $runresult->NumAssertions != 1 ) {
                ?>s<?php
            }
            ?> pass)<?php
            echo "\n";
            foreach ( $runresult as $assertresult ) {
                if ( $assertresult instanceof AssertResultFailedByException ) {
                    ?>Unanticipated fail: <?php
                    echo $assertresult->Message . "\n";
                    // $water->callstack( $water->callstack_lastword( $assertresult->Callstack ) );
                    break;
                }

                if ( !$assertresult->Success ) {
                    echo "\n----------------\n";
                    echo $assertresult->Message . "\n";
                    ?>Expected: <?php
                    ob_start();
                    Test_VarDump( $assertresult->Expected );
                    echo ob_get_clean();
                    ?>Actual: <?php
                    ob_start();
                    Test_VarDump( $assertresult->Actual );
                    echo ob_get_clean();
                }
            }
        }
    }

?>
