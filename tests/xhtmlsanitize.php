<?php
    final class TestXHTMLSanitizer extends Testcase {
        public function TestClassesExist() {
            $this->Assert( class_exists( 'XHTMLSanitizer' ) );
            $this->Assert( class_exists( 'XHTMLSaneTag' ) );
            $this->Assert( class_exists( 'XHTMLSaneAttribute' ) );
        }
        public function TestMethodsExist() {
            $sanitizer = New XHTMLSanitizer();
            $this->Assert( method_exists( $sanitizer, 'SetSource' ) );
            $this->Assert( method_exists( $sanitizer, 'Sanitize' ) );
            $this->Assert( method_exists( $sanitizer, 'GetXHTML' ) );
            $this->Assert( method_exists( $sanitizer, 'AllowTag' ) );
            $tag = New XHTMLSaneTag( 'b' );
            $this->Assert( method_exists( $tag, 'AllowAttribute' ) );
            $attribute = New XHTMLSaneAttribute( 'class' );
        }
        public function TestSimple() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( '' );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The empty string should be remain unchanged' );
            $sanitizer->SetSource( 'Hello, world!' );
            $sanitizer->Sanitize();
            $this->AssertEquals( 'Hello, world!', $sanitizer->GetXHTML(), 'Failed to sanitize a simple string' );
            $sanitizer->SetSource( 'While class Bar offers a generalized behavior in function Go, it allows specialization, if the derived class desires, by making its Run function virtual. Notice that the proper function is invoked because the instantiation is done as Bar, not as Foo, even though the Go function is defined within Foo.' );
            $sanitizer->Sanitize();
            $this->AssertEquals( 'While class Bar offers a generalized behavior in function Go, it allows specialization, if the derived class desires, by making its Run function virtual. Notice that the proper function is invoked because the instantiation is done as Bar, not as Foo, even though the Go function is defined within Foo.', $sanitizer->GetXHTML(), 'Failed to sanitize a simple, longer, string' );
        }
        public function TestWrongTypes() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( 5 );
            $sanitizer->Sanitize();
            $this->AssertEquals( '5', $sanitizer->GetXHTML(), 'The sanitizer should convert integer source to string, and raise appropriate notices' );
            $sanitizer->SetSource( false );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The sanitizer should convert boolean false source to string, and raise appropriate notices' );
            $sanitizer->SetSource( true );
            $sanitizer->Sanitize();
            $this->AssertEquals( '1', $sanitizer->GetXHTML(), 'The sanitizer should convert boolean true source to string, and raise appropriate notices' );
            $sanitizer->SetSource( array() );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The sanitizer should discard non-scalar sources, and raise appropriate warnings' );
            $sanitizer->SetSource( $this );
            $sanitizer->Sanitize();
            $this->AssertEquals( '', $sanitizer->GetXHTML(), 'The sanitizer should discard non-scalar sources, and raise appropriate warnings' );
        }
        public function TestEntities() {
            // http://www.w3schools.com/tags/ref_entities.asp
            
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( 'Hello &amp; world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello &amp; world!', $result, 'Valid entities should remain unchanged (&amp;)' );
            $sanitizer->SetSource( 'Hello & world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello &amp; world!', $result, 'Invalid entities should be escaped to produce valid XHTML (&)' );
            $sanitizer->SetSource( 'Hello &amp world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello &amp;amp world!', $result, 'Invalid entities should be escaped to produce valid XHTML (&amp)' );
            $sanitizer->SetSource( '&Ograve;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&Ograve;', $result, 'Valid entities should remain unchanged (&Ograve;)' );
            $sanitizer->SetSource( 'haha&Iacute;&OElig;&spades;heh&thetasym;&cent;&#8217;&#X03bb;&#x03BB;&#955;hoho' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'haha&Iacute;&OElig;&spades;heh&thetasym;&cent;&#8217;&#X03bb;&#x03BB;&#955;hoho', $result, 'Valid entities should remain unchanged (multiple)' );
            $sanitizer->SetSource( '&;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;;', $result, 'Invalid entities should be escaped to produce valid XHTML (&;)' );
            $sanitizer->SetSource( '&#18237910392839;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#18237910392839;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#18237910392839;)' );
            $sanitizer->SetSource( '&#x112111211;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#x112111211;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#x112111211;)' );
            $sanitizer->SetSource( '&#x1121112111;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#x1121112111;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#x112111211;)' );
            $sanitizer->SetSource( '&#xtata;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#xtata;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#xtata;)' );
            $sanitizer->SetSource( '&#Xtata;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#Xtata;', $result, 'Invalid entities should be escaped to produce valid XHTML (&#Xtata;)' );
            $sanitizer->SetSource( '&#x121;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&amp;#x121;', $result, 'Odd length should not be allowed for hexadecimal entities (&#x121;)' );
            $sanitizer->SetSource( '&#xf00d;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#xf00d;', $result, 'Valid entities should remain unchanged (&#xf00d;)' );
            $sanitizer->SetSource( '&#Xf00d;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#Xf00d;', $result, 'Valid entities should remain unchanged (&#Xf00d;)' );
            $sanitizer->SetSource( '&#xF00D;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#xF00D;', $result, 'Valid entities should remain unchanged (&#xF00D;)' );
            $sanitizer->SetSource( '&#XF00D;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#XF00D;', $result, 'Valid entities should remain unchanged (&#XF00D;)' );
            $sanitizer->SetSource( '&apos;&quot;&gt;&lt;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&#XF00D;', $result, 'Valid entities should remain unchanged (&#XF00D;)' );
        }
        public function TestWhitespace() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( '                       ' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple spaces should be consumed to a single space' );
            $sanitizer->SetSource( "\n\n\n\n" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple new lines should be consumed to a single space' );
            $sanitizer->SetSource( "\n  \n          \n\n      " );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple new lines and spaces should be consumed to a single space' );
            $sanitizer->SetSource( "\n\r\n\r\n\r\r\r \r\n\n\n \t\t    \t\n\r" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( ' ', $result, 'Multiple whitespace characters should be consumed to a single space' );
            $sanitizer->SetSource( "hahaha          haha\n\n\n\n\n\nhoho" );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'hahaha haha hoho', $result, 'Whitespace should be replaced with a single space' );
        }
        public function TestNoTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->SetSource( 'Hello <b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Undefined tags should be removed' );
            $sanitizer->SetSource( 'Hello <em><b>world</b></em>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Undefined tags should be removed' );
            $sanitizer->SetSource( '<div>Hello <em><b>world</b></em>!</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Undefined tags should be removed' );
            $sanitizer->SetSource( '<div>Hello   <em> <b> world </b> </em> ! </div>!!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world ! !!', $result, 'Undefined tags should be removed' );
        }
        public function TestSimpleTag() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->SetSource( '<b></b>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b></b>', $result, 'Simple empty tag should remain unchanged' );
            $sanitizer->SetSource( '<b />' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;b /&gt;', $result, 'Tags with mandatory content should be explicitly closed by the user' );
            $sanitizer->SetSource( 'Hello <b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b>world</b>!', $result, 'Simple use of a defined tag should be allowed' );
            $sanitizer->SetSource( '<b>Hello</b> <b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b>Hello</b> <b>world</b>!', $result, 'Simple use of ôòï defined tag should be allowed' );
            $sanitizer->SetSource( '<b>Hello </b><b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b>Hello </b><b>world</b>!', $result, 'Simple use of whitespace within defined tags should be allowed' );
        }
        public function TestUnclosedTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->SetSource( 'Hello <b>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b>world!</b>', $result, 'Unclosed tags should auto-close' );
            $sanitizer->SetSource( 'Hello <em>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello world!', $result, 'Disallowed tags should not auto-close' );
            $sanitizer->SetSource( 'Hello <b><em><b>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b><b>world!</b></b>', $result, 'Allowed tags should auto-close when nested and contain disallowed tags' );
            $sanitizer->SetSource( 'Hello <b><b>world</b>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <b><b>world</b>!</b>', $result, 'Allowed tags should auto-close when nested' );
        }
        public function TestTwoTags() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'strong' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'em' ) );
            $sanitizer->SetSource( 'Hello <strong><em>world</em></strong>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world</em></strong>!', $result, 'Two allowed tags should not be filtered' );
            $sanitizer->SetSource( 'Hello <strong><em>world!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world!</em></strong>', $result, 'Two allowed tags should auto-close' );
            $sanitizer->SetSource( 'Hello <strong><em>world</em>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world</em>!</strong>', $result, 'Allowed tag should auto-close when other allowed tag exists within it' );
            $sanitizer->SetSource( 'Hello <strong><em>world!</strong>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world!</em></strong>', $result, 'Auto-close should observe nesting rules' );
            $sanitizer->SetSource( 'Hello <strong><em>world</strong>!' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong><em>world</em></strong>!', $result, 'Auto-close should observe nesting rules' );
            $sanitizer->SetSource( 'Hello <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( 'Hello <strong>world</strong>!&lt;/em&gt;', $result, 'Closed tags that did not open should be escaped' );
            $sanitizer->SetSource( '<em>Hello</em> <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em>Hello</em> <strong>world</strong>!&lt;/em&gt;', $result, 'Closed tags that did not open should be escaped' );
            $sanitizer->SetSource( '<em><em>Hello</em> <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em><em>Hello</em></em> <strong>world</strong>!&lt;/em&gt;', $result, 'Closed tags that did not open should be escaped, even when auto-closing occurred' );
            $sanitizer->SetSource( '<em><em>Hello <strong>world</strong>!</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em><em>Hello <strong>world</strong>!</em></em>', $result, 'Auto-closing should not be affected by interfering tags' );
        }
        public function TestTagsWithEntities() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'strong' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'em' ) );
            $sanitizer->SetSource( '<em><em>Hello &amp;<strong>world</strong>!</em></em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em><em>Hello &amp;<strong>world</strong>!</em></em>', $result, 'Allowed tags and allowed entities should remain unchanged' );
            $sanitizer->SetSource( '&lt;em&gt;Hello&lt;/em&gt;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;em&gt;Hello&lt;/em&gt;', $result, 'Allowed entities should remain unchanged, even when they look like tags' );
            $sanitizer->SetSource( '&lt;em&gt;Hello</em>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;em&gt;Hello&lt/em&gt;', $result, 'Allowed entities should remain unchanged while closed tags that did not open are escaped' );
            $sanitizer->SetSource( '<em>Hello&lt;/em&gt;' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<em>Hello&lt;/em&gt;</em>', $result, 'Auto-closing should remain unaffected from entities' );
        }
        public function TestInvalidTags() {
            // http://www.w3schools.com/tags/default.asp
            
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'koko' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'lala' ) );
            $sanitizer->SetSource( '<koko><lala /> liruliru</koko>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;koko&gt;&lt;lala /&gt; liruliru&lt;koko&gt;', $result, 'Invalid tags (koko, lala, and so forth) should not be allowed even if explicitly specified; appropriate warnings should be raised' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'dir' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'applet' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'isindex' ) );
            $sanitizer->SetSource( '<dir>haha<applet>yes</applet></dir>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '&lt;dir&gt;haha&lt;applet&gt;yes&lt;/applet&gt;&lt;/dir&gt;', $result, 'Deprecated tags (dir, applet, isindex, and so forth) should not be allowed even if explicitly specified; appropriate warnings should be raised, distinguishing them from invalid tags' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'b' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'script' ) );
            $sanitizer->SetSource( '<b>haha</b><script>alert("XSS!");</script>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<b>haha</b>&lt;script&gt;alert("XSS!");&lt;script&gt;', $result, 'Insecure tags (script, link, style) should be disallowed even if explicitly specified; appropriate warnings should be raised' );
        }
        public function TestBlocks() {
            // http://en.wikipedia.org/wiki/HTML_element#Block
            
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'div' ) );
            $sanitizer->AllowTag( New XHTMLSaneTag( 'span' ) );
            $sanitizer->SetSource( '<span></span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span></span>', $result, 'Allowed tags should remain at place even when empty' );
            $sanitizer->SetSource( '<div><span></span></div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<div><span></span></div>', $result, 'Span within div is allowed' );
            $sanitizer->SetSource( '<span><div></div></span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span></span>', $result, 'Div within spam is not allowed (block in inline)' );
            $sanitizer->SetSource( '<div>Hello <span>my</span> friend</div>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<div>Hello my friend</div>', $result, 'Span within div consumption should preserve content' );
            
            $sanitizer->AllowTag( New XHTMLSaneTag( 'form' ) );
            $sanitizer->SetSource( '<span><form>Ye<span>aa</span>ah</form></span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span>Ye<span>aa</span>ah</span>', $result, 'Block elements should not be allowed within inline; content should be preserved' );
        }
        public function TestNoAttributes() {
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( New XHTMLSaneTag( 'span' ) );
            $sanitizer->SetSource( '<span class="standing">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span>by the way</span>', $result, 'Attributes not explicitly declared within allowed tags should be eliminated' );
        }
        public function TestSimpleAttributes() {
            $span = New XHTMLSaneTag( 'span' );
            $span->AllowAttribute( New XHTMLSaneAttribute( 'class' ) );
            $sanitizer = New XHTMLSanitizer();
            $sanitizer->AllowTag( $span );
            $sanitizer->SetSource( '<span class="standing">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="standing">by the way</span>', $result, 'Attributes explicitly declared within allowed tags should remain unchanged' );
            $sanitizer->SetSource( '<span class="">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="">by the way</span>', $result, 'Allowed attributes should remain unchanged even when empty' );
            $sanitizer->SetSource( '<span class="" title="">by the way</span>' );
            $result = $sanitizer->GetXHTML();
            $this->AssertEquals( '<span class="">by the way</span>', $result, 'Allowed attributes should remain unchanged even when empty' );
        }
    }
    
    return new TestXHTMLSanitizer();
?>
