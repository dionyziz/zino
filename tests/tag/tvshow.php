<?php
    global $libs;
    $libs->Load( 'tvshowtag' );

    final class TestTvShowTag extends TestTag {
        protected $mClass;

        public function TestTvShowTag() {
            $this->mClass = 'TvShowTag';
            $this->TestTag();
        }
    }

    return New TestTvShowTag();
?>
