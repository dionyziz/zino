<?php
    class TestWYSIWYG extends Testcase {
        protected $mAppliesTo = 'libs/wysiwyg';

        public function TestLinks() {
            $this->AssertEquals( '<a href="http://www.google.com/">http://www.google.com/</a>', WYSIWYG_Links( 'http://www.google.com/' ) );
            $this->AssertEquals( '<a href="https://foo.bar.org/?blah=baz&amp;alpha=beta">https://foo.bar.org/?blah=baz&amp;alpha=beta</a>', WYSIWYG_Links( 'https://foo.bar.org/?blah=baz&amp;alpha=beta' ) );
            $this->AssertEquals( 'Hello, <a href="http://en.wikipedia.org/wiki/World">http://en.wikipedia.org/wiki/World</a>!', WYSIWYG_Links( 'Hello, http://en.wikipedia.org/wiki/World!' ) );
        }
    }
?>
