<?php
    global $libs;
    $libs->Load( 'videogametag' );

    final class TestVideoGameTag extends TestTag {
        protected $mClass;

        public function TestVideoGameTagTag() {
            $this->mClass = 'VideoGameTag';
            $this->TestTag();
        }
    }

    return New TestVideoGameTag();
?>
