<?php

    class TestWYSIWYG extends Testcase {
        protected $mAppliesTo = 'libs/wysiwyg';

        public function TestLinks() {
            $this->AssertEquals( '<a href="http://www.google.com/">http://www.google.com/</a>', WYSIWYG_Links( 'http://www.google.com/' ) );
            $this->AssertEquals( '<a href="https://foo.bar.org/?blah=baz&amp;alpha=beta">https://foo.bar.org/?blah=baz&amp;alpha=beta</a>', WYSIWYG_Links( 'https://foo.bar.org/?blah=baz&amp;alpha=beta' ) );
            $this->AssertEquals( 'Hello, <a href="http://en.wikipedia.org/wiki/World">http://en.wikipedia.org/wiki/World</a>!', WYSIWYG_Links( 'Hello, http://en.wikipedia.org/wiki/World!' ) );
        }
        
        public function TestSmileys() {
            global $xc_settings;

            $this->AssertEquals( '<img src="' . $xc_settings[ 'staticimagesurl' ] . 'emoticons/smile.png" alt=":-)" title=":-)" class="emoticon" width="22" height="22" />', WYSIWYG_Smileys( ':-)' ) );
            $this->AssertEquals( 'lol <img src="' . $xc_settings[ 'staticimagesurl' ] . 'emoticons/tongue.png" alt=":P" title=":P" class="emoticon" width="22" height="22" /> at you', WYSIWYG_Smileys( 'lol :P at you' ) );
            $this->AssertEquals( 'Watch this <img src="' . $xc_settings[ 'staticimagesurl' ] . 'emoticons/airplane.png" alt=":airplane:" title=":airplane:" class="emoticon" width="22" height="22" />!', WYSIWYG_Smileys( 'Watch this :airplane:!' ) );
            $this->AssertEquals( '<img src="' . $xc_settings[ 'staticimagesurl' ] . 'emoticons/wink.png" alt=";-)" title=";-)" class="emoticon" width="22" height="22" />', WYSIWYG_Smileys( ';-)' ) );
        }
        
        public function TestText() {
            global $xc_settings;

            $this->AssertEquals(
                '<a href="http://foo/&amp;bar">http://foo/&amp;bar</a> <img src="' . $xc_settings[ 'staticimagesurl' ] . 'emoticons/omg.png" alt=":wow:" title=":wow:" class="emoticon" width="22" height="22" />',
                WYSIWYG_TextProcess( 'http://foo/&bar :wow:' )
            );
            $this->AssertEquals(
                'Look at that: <a href="http://domain/#anchor">http://domain/#anchor</a> <img src="' . $xc_settings[ 'staticimagesurl' ] . 'emoticons/film.png" alt=":film:" title=":film:" class="emoticon" width="22" height="22" />!!',
                WYSIWYG_TextProcess( 'Look at that: http://domain/#anchor :film:!!' )
            );
        }
    }

    return New TestWYSIWYG();

?>
