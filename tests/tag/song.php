<?php
    global $libs;
    $libs->Load( 'songtag' );

    final class TestSongTag extends TestTag {
        protected $mClass;

        public function TestSongTag() {
            $this->mClass = 'SongTag';
            $this->TestTag();
        }
    }

    return New TestSongTag();
?>
