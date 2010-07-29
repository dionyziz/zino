#!/usr/bin/php
<?php

    function clude( $path ) {
        static $included = array();
        if ( !isset( $included[ $path ] ) ) {
            $included[ $path ] = true;
            return include $path;
        }
        return true;
    }

    function ParseArguments( $args, $validTestsByName ) {
        $runtests = array();

        array_shift( $args );
        foreach ( $args as $i => $value ) {
            switch ( $value ) {
                case "--all":
                    foreach ( $validTestsByName as $name => $testcase ) {
                        $runtests[] = $name;
                    }
                    break;
                default:
                    $runtests[] = $value;
            }
        }

        return $runtests;
    }

    function AddTestcases( &$tester, $runtests, $validTestsByName ) {
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

        return $indexByName;
    }

    function ViewTestcaseResult( $name, $testresult ) {
        echo $name;
        ?>: <?php
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
            return;
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
            ViewRunResult( $runresult );
        }
    }

    function ViewRunResult( $runresult ) {
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
            return;
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
            ViewAssertResult( $assertresult );
        }
    }

    function ViewAssertResult( $assertresult ) {
        if ( $assertresult instanceof AssertResultFailedByException ) {
            ?>Unanticipated fail: <?php
            echo $assertresult->Message . "\n";
            // $water->callstack( $water->callstack_lastword( $assertresult->Callstack ) );
            return;
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

    $xdebugAvailable = false;
    if ( function_exists( 'xdebug_enable' ) ) {
        $xdebugAvailable = true;
        xdebug_enable();
    }
    
    global $settings;
    $settings = include 'settings.php';

    include 'models/water.php';
    include 'models/unittest.php';
    include 'models/db.php';

    $tester = New Tester();

    $validTestsByName = Test_GetTestcasesByName();
    $runtests = ParseArguments( $argv, $validTestsByName );
    $indexByName = AddTestcases( $tester, $runtests, $validTestsByName );

    $tester->Run();
    $testcaseResults = $tester->GetResults();

    foreach ( $validTestsByName as $i => $testcase ) {
        $name = $testcase->Name;
        if ( !isset( $indexByName[ $name ] ) ) {
            continue;
        }
        $testresult = $testcaseResults[ $indexByName[ $name ] ];
        ViewTestcaseResult( $name, $testresult );
    }

    if ( $xdebugAvailable ) {
        printf( "\ntotal time: %0.3f seconds, memory peak: %.0fKB\n", xdebug_time_index(), xdebug_peak_memory_usage() / 1024 );
    }

?>
