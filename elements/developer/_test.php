<?php
    function ElementDeveloperTest( tStringArray $testcases ) {
        global $libs;
        global $page;
        
        $page->SetTitle( 'Unit Test' );
        $libs->Load( 'rabbit/unittest' );
        $testcases = Test_GetTestcases()
        ?><ul><?php
        foreach ( $testcases as $testcase ) {
            ?><li><?php
            echo $testcase->Name();
            ?></li><?php
        }
        ?></ul><?php
    }
?>
