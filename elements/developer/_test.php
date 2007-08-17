<?php
    function ElementDeveloperTest( tStringArray $testcases ) {
        global $libs;
        global $page;
        
        $runtests = $testcases->Get();
        
        $page->SetTitle( 'Unit Test' );
        $libs->Load( 'rabbit/unittest' );
        $validtests = Test_GetTestcases();
        
        ?><br />Select which tests to run:<br />
        <form action="" method="get">
        <ul><?php
        foreach ( $validtests as $testcase ) {
            ?><li><input type="checkbox" name="testcases" value="<?php
            echo htmlspecialchars( $testcase->Name() );
            ?>" /><?php
            echo $testcase->Name();
            ?></li><?php
        }
        ?></ul><br />
        <input type="submit" value="Test!" />
        </form><?php
    }
?>
