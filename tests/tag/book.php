<?php
    global $libs;
    $libs->Load( 'booktag' );

    require_once "tests/tag/tag.php";

    final class TestBookTag extends TestTag {
        protected $mClass;

        public function TestBookTag() {
            $this->mClass = 'BookTag';
            $this->TestTag();
        }
    }

    return New TestBookTag();
?>
