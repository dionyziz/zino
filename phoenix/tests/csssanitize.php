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
            );
        }
        /*

            
                'div#freeze', 'div.box',
                p.p
                p.p p.p p.p
                p#p a img strong.bold:hover
                body.box#p:hover table tr td div#foo.bar p span strong a:hover img
                a#id:focus, a.class:active, p a:hover, p p p p a:visited, p:first-child
                *, *.boo
                .boo #foo, #foo .boo
                div + p:first-letter, div * p:before
            $reduced = str_replace( ' ', '', $valid );
            $this->AssertEquals( $reduced, $this->mSanitizer->GetCSS(), 'Some valid selectors were marked as invalid' );
        }
        public function Test
        */

    }
?>
