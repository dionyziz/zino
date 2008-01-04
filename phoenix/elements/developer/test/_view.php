<?php
    function ElementDeveloperTestView( tStringArray $runtests ) {
        global $libs;
        global $page;
        global $water;
        
        $libs->Load( 'rabbit/unittest' );
        $page->SetTitle( 'Unit Test' );
        $page->AttachStylesheet( 'css/rabbit/unittest.css' );
        
        $validtests = Test_GetTestcases();
        $validtestsbyname = array();
        foreach ( $validtests as $testcase ) {
            $validtestsbyname[ $testcase->Name ] = $testcase;
        }
        
        $tester = New Tester();
        $testsran = array();
        $indexbyname = array();
        $i = 0;
        foreach ( $runtests as $testname ) {
            $testname = $testname->Get();
            if ( !isset( $validtestsbyname[ $testname ] ) ) {
                $water->Warning( 'Testname "' . $testname . '" which you tried to run is not a valid testname' );
            }
            else {
                $tester->AddTestcase( $validtestsbyname[ $testname ] );
                $indexbyname[ $testname ] = $i;
                $testsran[ $testname ] = true;
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
            Element( 'developer/test/case', $testcase, $testsran, $testcaseresults, $indexbyname );
        }
        ?></ul><br />
        <input type="submit" value="Test!" />
        </form><?php
    }
?>
