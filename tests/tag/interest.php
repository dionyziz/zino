<?php
    global $libs;
    $libs->Load( 'interesttag' );

    final class TestInterestTag extends TestTag {
        protected $mClass;

        public function TestInterestTag() {
            $this->mClass = 'InterestTag';
            $this->TestTag();
        }
    }

    return New TestInterestTag();
?>
