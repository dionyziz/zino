<?php
    /* 
        Developer: Dionyziz 
    */
    
    function Sanitize_ArticleSmall( $xhtml ) {
        return '(small article)'; // $xhtml; // substr( $xhtml, 0, 400 );
    }
    
    final class XHTMLSanitizer {
        private $mInputCode;
        private $mCode;
        
        public function XHTMLSanitizer( $xhtml ) {
            $this->mCode = $this->mInputCode = $xhtml;
        }
        public function Sanitize() {
            $autoclose = array( 'br', 'img' );
            
            $this->RemoveComments();
            $i = 0;
            $tagstack = array();
            while ( $i < strlen( $this->mCode ) ) { // O( N ) linear
                switch ( $this->mCode{ $i } ) {
                    case '<':
                        ++$i;
                        if ( $this->mCode{ $i } == '/' ) {
                            // closing </tag>
                            $tagname = '';
                            do {
                                ++$i;
                                $tagname .= $this->mCode{ $i };
                            } while ( $this->mCode{ $i } != '>' );
                            if ( !preg_match( '#^[a-zA-Z]+$#', $tagname ) ) {
                                return false; // invalid tag name
                            }
                            $tagname = strtolower( $tagname );
                            if ( !count( $tagstack ) ) {
                                return false; // closing a tag that wasn't opened
                            }
                            $pop = array_pop( $tagstack );
                            if ( $pop != $tagname ) {
                                return false; // <i>improper <b>nesting</i></b>
                            }
                        }
                        else {
                            // opening <tag yadda="foo">
                            // or open-and-close <tag yadda="foo" />
                        }
                    case '&':
                    default:
                        continue;
                }
                ++$i;
            }
        }
        private function RemoveComments() {
            $endpos = 0;
            while ( ( $startpos = strpos( $this->mCode , '<!--' , $endpos ) ) !== false ) {
                $endpos = strpos( $this->mCode , '-->' );
                if ( $endpos === false ) {
                    $endpos = strlen( $this->mCode );
                }
                if ( $endpos >= strlen( $this->mCode ) ) {
                    $this->mCode = substr( $this->mCode , 0 , $startpos );
                }
            }
        }
    }
    
?>
