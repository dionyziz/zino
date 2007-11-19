<?php
    global $libs;
    $libs->Load( 'movietag' );

    final class TestMovieTag extends TestTag {
        protected $mClass;

        public function TestMovieTag() {
            $this->mClass = 'MovieTag';
            $this->TestTag();
        }
    }

    return New TestMovieTag();
?>
