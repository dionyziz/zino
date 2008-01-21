<?php
    class TestEvent extends TestCase {
        public function SetUp() {
            global $libs;
            
            $libs->Load( 'interesttag' );
        }
    }

    return New TestEvent();
?>
