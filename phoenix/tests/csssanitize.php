<?php
    class TestCSSSanitizer extends Testcase {
        private $mSanitizer;

        public function TestClassesExist() {
            $this->Assert( class_exists( 'CSSSanitizer' ) );
            $this->mSanitizer = New CSSSanitizer();
        }
        public function TestMethodsExist() {
            $this->Assert( method_exists( $this->mSanitizer, 'SetSource' ) );
            $this->Assert( method_exists( $this->mSanitizer, 'GetCSS' ) );
        }
        public function TestEmpty() {
            $this->mSanitizer->SetSource( '' );
            $this->AssertEquals( '', $this->mSanitizer->GetCSS(), 'The empty string should remain unchanged' );
        }
        public function TestSimple() {
            $valid = 'div.test{font-size:120%;}';
            $this->mSanitizer->SetSource( $valid );
            $this->AssertEquals( $valid, $this->mSanitizer->GetCSS(), 'A simple valid testcase was not left unchanged' );
        }
        public function TestWhitespace() {
            $valid = 'div.test { font-size : 120%  ;   }';
            $this->mSanitizer->SetSource( $valid );
            $reduced = str_replace( ' ', '', $valid );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'The whitespace of a simple testcase was not reduced properly' );
        }
        public function TestMissingSemicolon() {
            $valid = 'div.test{font-size:120%}';
            $this->mSanitizer->SetSource( $valid );
            $reduced = 'div.test{font-size:120%;}';
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A missing semicolon was not formerly inserted after attribute value' );
        }
        public function TestMultipleAttributes() {
            $valid = 
                'div.test {
                    font-size: 120%;
                    font-weight: bold;
                    text-decoration: underline;
                    border: 1px solid green;
                }';
            $reduced = str_replace( ' ', '', $valid );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A simple multiattribute rule was not left unchanged' );
        }
        public function TestMultipleRules() {
            $valid =
                'div.test {
                    border: 1px solid red;
                }
                div.foo {
                    font-size: 120%;
                    font-weight: bold;
                }
                p.bar {
                    border: 1px solid green;
                }';
            $reduced = str_replace( ' ', '', $valid );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A simple testcase with multiple rules was not left unchanged' );
        }
        public function TestDuplicateSelectors() {
            $valid =
                'div.test {
                    background-color: black;
                }
                div.test {
                    font-size: 120%;
                }';
            $reduced =
                'div.test {
                    background-color: black;
                    font-size: 120%;
                }';
            $reduced = str_replace( ' ', '', $reduced );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A duplicate selector should cause a merge' );

            $valid =
                'div.test {
                    background-color: black;
                }
                div.test {
                    background-color: blue;
                }';
            $reduced =
                'div.test {
                    background-color: blue;
                }';
            $reduced = str_replace( ' ', '', $reduced );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A duplicate selector should cause a merge of attributes too' );
        }
        public function TestDuplicateAttributes() {
            $valid = 'div.test{font-size:120%;font-size:120%;}';
            $this->mSanitizer->SetSource( $valid );
            $reduced = 'div.test{font-size:120%;}';
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A duplicate attribute has not been reduced' );
            
            $valid = 'div.test{font-size:120%;font-size:140%;}';
            $this->mSanitizer->SetSource( $valid );
            $reduced = 'div.test{font-size:140%;}';
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A duplicate attribute with a different value was not reduced' );

            $valid =
                'div.test {
                    font-weight: bold;
                    font-size: 120%;
                    text-decoration: underline;
                    font-size: 140%;
                    border: 1px solid red;
                }';
            $reduced =
                'div.test {
                    font-weight: bold;
                    font-size: 140%;
                    text-decoration: underline;
                    border: 1px solid red;
                }';
            $reduced = str_replace( ' ', '', $reduced );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'A duplicate attribute with a different value and intermediate attributes was not reduced properly' );
        }
        private function ValidateSelectors( Array $selectors, $message, $good = true ) {
            foreach ( $selectors as $selector ) {
                $css = $selector . '{font-weight:bold;}';
                if ( $good ) {
                    $expected = $css;
                }
                else {
                    $expected = '';
                }
                $this->mSanitizer->SetSource( $css );
                $warn = sprintf( $message, $selector );
                $this->AssertEquals( $expected, $this->mSanitizet->GetCSS(), $warn );
            }
        }
        public function TestValidTags() {
            $valid = array( 
                'a', 'abbr', 'blockquote', 'br', 'caption',
                'dd', 'del', 'div', 'dfn', 'dl', 'dt', 'em',
                'fieldset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'img', 'ins', 'kdb', 'label', 'legend',
                'li', 'map', 'ol', 'option', 'p', 'q', 'sub', 'sup',
                'strong', 'table', 'td', 'tr', 'th', 'thead', 'textarea',
                'ul', 'object'
            );
            $this->ValidateSelectors( $valid, 'Simple tag selector "%s" was not allowed, while valid!' );
        }
        public function TestDeprecatedTags() {
            $invalid = array(
                'b', 'font', 'i', 'big', 'small', 'hr', 'u', 'center',
                'menu', 'layer', 'blink', 'marquee'
            );
        }
        public function TestInvalidTags() {
            $invalid = array(
                'haha', 'hoho', 'bad', 'evil', 'notnice'
            );
            $this->ValidateSelectors( $invalid, 'Simple tag selector "%s" refers to an invalid tag and should not be accepted', false );
        }
        public function TestValidClasses() {
            $valid = array(
                '.heh', '.foo', '.bar',
                'p.test', 'p.foo', 'div.cool',
                'table.blob', 'p.p', '.p',
                'blockquote.blockquote'
            );
            $this->ValidateSelectors( $valid, 'Simple class selector "%s" was not allowed!' );
        }
        public function TestInvalidClasses() {
            $invalid = array(
                'badtag.goodclass', 'xkcd.dckx', 'bwahahaha.',
                '.', '..', '...', '.!', '!.', '!@#!..'
            );
            $this->ValidateSelectors( $invalid, 'This class selector "%s" should not have been allowed since it is invalid', false );
        }
        public function TestValidIds() {
            $valid = array(
                'p#test', '#foo', 'blockquote#blockquote', 'p#a',
                'p#p', 'strong#em'
            );
            $this->ValidateSelectors( $valid, 'This id selector "%s" was not allowed while valid' );
        }
        public function TestInvalidIds() {
            $invalid = array(
                'ppppppppppppp#test', 'haahaha#test', 'boo.fuzz#blob',
                '##', '#', '.#', '#.'
            );
            $this->ValidateSelectors( $invalid, 'This id selector "%s" should not have been allowed since it is invalid', false );
        }
        public function TestValidPseudoClasses() {
            $valid = array(
                'a:hover', 'p:hover', 'a.boo:hover', 'a#bar:hover',
                'a.boo#bar:hover', 'div:hover', 'a:link', 'a:visited',
                'a:active', 'input:focus', '*:hover', ':link'
            );
            $this->ValidateSelectors( $valid, 'This pseudoclass selector "%s" was not allowed while valid' );
        }
        public function TestInvalidPseudoClass() {
            $invalid = array(
                'a:hoooover', ':boat', '$:boat', '#:boat', '#bar:boat',
                'p:visited', 'div:link', 'blockquote.p#p:visited', '##',
                '::', '#:', ':#'
            );
            $this->ValidateSelectors( $invalid, 'This pseudoclass selector "%s" should not have been allowed since it is invalid', false );
        }
        public function TestValidDescendants() {
            $valid = array(
                'p strong', 'strong p', 'p p p p p p p p p p p p p p',
                'a span a span a span', 'a:hover span', 'a:link span',
                'strong span strong span span strong.strong', 'strong.strong a#id span:hover input:focus',
                'p#p p.p', 'blockquote #blockquote', '#blockquote blockquote', 'blockquote :hover',
                'blockquote .blockquote', '.blockquote blockquote', '.a .b .c', '.a #b .c #d'
            );
            $this->ValidateSelectors( $valid, 'This selector with descendants "%s" was now allowed while valid' );
        }
        public function TestInvalidDescendants() {
            $invalid = array(
                'p p p p p:visited', 'blockquote #', 'p .', '. .', '.foo .', '# #',
                '#blah #', '#p :hoooover'
            );
            $this->ValidateSelectors( $invalid, 'This selector with descendants "%s" should not have been allowed since it is invalid', false );
        }
        private function ValidateAttributes( Array $attributes, $message, $good = true ) {
            foreach ( $attributes as $attribute => $value ) {
                $css = 'div{font-weight:bold;' . $attribute . ':' . $value . ';text-decoration:underline;}';
                if ( $good ) {
                    $expected = $css;
                }
                else {
                    $css = 'div{font-weight:bold;text-decoration:underline;}';
                }
                $this->mSanitizer->SetSource( $css );
                $warn = sprintf( $message, $selector );
                $this->AssertEquals( $expected, $this->mSanitizet->GetCSS(), $warn );
            }
        }
        private function ValidateColorAttribute( $attribute ) {
            $css = 'div{' . $attribute . ':#1234fe;}';
            $this->mSanitizer->SetSource( $css );
            $this->AssertEquals( $css, $this->mSanitizer->GetCSS(), '"' . $attribute . '", a color attribute, must support 6-digit hex codes' );

            $css = 'div{' . $attribute . ':#1e3;}';
            $this->mSanitizer->SetSource( $css );
            $this->AssertEquals( $css, $this->mSanitizer->GetCSS(), '"' . $attribute . '", a color attribute, must support 3-digit hex codes' );

            $css = 'div{' . $attribute . ':rgb(1,2,3);}';
            $this->mSanitizer->SetSource( $css );
            $this->AssertEquals( $css, $this->mSanitizer->GetCSS(), '"' . $attribute . '". a color attribute, must support rgb codes' );

            $css = 'div{' . $attribute . ':#1234fq;}';
            $this->mSanitizer->SetSource( $css );
            $this->AssertEquals( $css, $this->mSanitizer->GetCSS(), '"' . $attribute . '", a color attribute, must fail for invalid 6-digit hex codes' );

            $css = 'div{' . $attribute . ':#1q3;}';
            $this->mSanitizer->SetSource( $css );
            $this->AssertEquals( $css, $this->mSanitizer->GetCSS(), '"' . $attribute . '", a color attribute, must fail for invalid 3-digit hex codes' );

            $css = 'div{' . $attribute . ':rgb(1029,2,3);}';
            $this->mSanitizer->SetSource( $css );
            $this->AssertEquals( $css, $this->mSanitizer->GetCSS(), '"' . $attribute . '". a color attribute, must fail for invalid rgb codes' );
        }
        public function TestColors() {
            $valid = array(
                'background-color', 'color', 'border-color',
                'border-top-color', 'border-right-color', 'border-bottom-color', 'border-left-color',
                'outline-color'
            );
            foreach ( $colors as $color ) {
                $this->ValidateColorAttribute( $color );
            }
        }
        public function TestValidAttributes() {
            $valid = array(
                'background-attachment' => array( 'scroll', 'fixed' ), 'background-image', 'background-position', 'background-repeat', 'background',
                'border-collapse', 'border-spacing', 'border-style', 'border-width',
                'border-top', 'border-right', 'border-bottom', 'border-left',
                'border-top-style', 'border-right-style', 'border-bottom-style', 'border-left-style',
                'border-top-width', 'border-right-width', 'border-bottom-width', 'border-left-width',
                'border',
                'top', 'right', 'bottom', 'left', 'width', 'height',
                'max-width', 'max-height', 'min-width', 'min-height',
                'caption-side', 'clear', 'clip',
                'content', 'counter-increment', 'counter-reset', 'cursor', 'direction', 'display',
                'empty-cells', 'float',
                'font-family', 'font-size', 'font-style', 'font-variant', 'font-weight', 'font',
                'letter-spacing', 'line-height',
                'list-style-image', 'list-style-position', 'list-style-type', 'list-style',
                'margin-top', 'margin-right', 'margin-bottom', 'margin-left', 'margin',
                'padding-top', 'padding-right', 'padding-bottom', 'padding-left', 'padding',
                'outline-style', 'outline-width', 'outline',
                'overflow', 'position', 'quotes', 'table-layout',
                'text-align', 'text-decoration', 'text-indent', 'text-transform',
                'vertical-align', 'visibility', 'white-space', 'word-spacing', 'z-index',
                'opacity'
            );
            $this->ValidateAttributes( $valid, 'Attribute "%s" ' );
        }
        public function TestEmptyRules() {
            $css = 'div{}';
            $this->AssertEquals( '', $this->mSanitizer->GetCSS(), 'An empty rule should be removed' );

            $css = '    div    {    }   ';
            $this->AssertEquals( '', $this->mSanitizer->GetCSS(), 'An empty rule should be removed, even when whitespace exists within' );

            $css =
                'div {
                     bad-attribute: bad-value;
                }';
            $this->AssertEquals( '', $this->mSanitizer->GetCSS(), 'An empty rule should be removed, even if emptyness was the result of an invalid attribute removal' );

            $css =
                'div {
                    background-color: bad-value;
                }';
            $this->AssertEquals( '', $this->mSanitizer->GetCSS(), 'An empty rule should be removed, even if emptyness was the result of an invalid attribute value removal' );
        }
        public function TestComments() {
        }
        public function TestInvalidAttributes() {
            $invalid = array(
                'azimuth', 'cue-after', 'cue-before', 'cue', 'elevation',
                'widows', 'orphans',
                'page-break-after', 'page-break-before', 'page-break-inside',
                'pause-before', 'pause-after', 'pause',
                'pitch-range', 'pitch', 'play-during', 'richness',
                'speak-header', 'speak-numeral', 'speak-punctuation', 'speak',
                'speech-rate', 'stress', 'voice-family', 'volume'
            );
        }
    }
?>
