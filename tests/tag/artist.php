<?php
    global $libs;
    $libs->Load( 'artisttag' );

    final class TestArtistTag extends TestTag {
        protected $mClass;

        public function TestArtistTag() {
            $this->mClass = 'ArtistTag';
            $this->TestTag();
        }
    }

    return New TestArtistTag();
?>
