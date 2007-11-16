<?php
    global $XHTMLSaneEntities;

    $XHTMLSaneEntities = array(
        '&amp;' => '&',
        '&lt;'  => '<',
        '&gt;'  => '>',
        // TODO
    );
    
    class XHTMLSanitizer {
        private $mSource;
        
        public function XHTMLSanitizer() {
        }
        public function SetSource( $source ) {
            global $water;
            
            if ( $source === true ) {
                $water->Notice( 'XHTMLSanitizer source was a boolean; converting to string' );
                $source = '1';
            }
            else if ( $source === false ) {
                $water->Notice( 'XHTMLSanitizer source was a boolean; converting to string' );
                $source = '';
            }
            else if ( is_array( $source ) ) {
                $water->Warning( 'XHTMLSanitizer source was an array; skipping' );
                $source = '';
            }
            else if ( is_object( $source ) ) {
                $water->Warning( 'XHTMLSanitizer source was an object; skipping' );
                $source = '';
            }
            $source = ( string )$source;
            $this->mSource = $source;
        }
        private function XHTMLSanitizer_GetEntity( $entity ) {
            // returns the literal of a given entity
            // or false if the entity is invalid
            // e.g. returns '<' for '&lt;'
            
            w_assert( is_string( $entity ) );
            w_assert( strlen( $entity ) );
            
            $entity = strtolower( $entity );
            
            if ( strlen( $entity ) < '&lt;' ) {
                return false; // too short entity
            }
            
            // try named entity
            if ( isset( $XHTMLSaneEntities[ $entity ] ) ) {
                return $XHTMLSaneEntities[ $entity ];
            }
            // named entity failed, try numeric entity
            if ( $entity{ 1 } == '#' ) {
                // numeric entity
                if ( $entity{ 2 } == 'x' ) {
                    // hex entity
                    // &#xb00b;
                    if ( strlen( $entity ) != strlen( '&#xdead;' ) ) {
                        return false; // invalid hex entity length
                    }
                    $hexnum = substr( $entity, strlen( '&#' ), strlen( 'dead' ) );
                    if ( !preg_match( '#^[a-f0-9]*$#', $hexnum ) ) {
                        return false; // hex entity contained non-hex digits
                    }
                    return chr( hexdec( $hexnum ) );
                }
                // else decimal entity
                if ( strlen( $entity ) > strlen( '&#007;' ) ) {
                    return false; // too long decimal entity
                }
                $decnum = substr( $entity, strlen( '&#' ), -strlen( ';' ) );
                if ( !preg_match( '#^[0-9]*$#', $decnum ) ) {
                    return false; // dec entity contains non-dec digits
                }
                return chr( $decnum );
            }
            return false;
        }
        private function RemoveComments( $htmlsource ) {
            global $water;
            
            if ( preg_match( '#\<\!--(.*?)\<\!--(.*?)--\>#', $htmlsource ) ) {
                $water->Warning( 'XHTMLSanitizer: Call me paranoid, but I found an opening HTML comment within another comment -- care checking?' );
            }
            return preg_replace( '#\<\!--(.*?)--\>#', '', $htmlsource );
        }
        private function ReduceWhitespace( $htmlsource ) {
            return preg_replace( "#()#", '' );
        }
        public function GetXHTML() {
            $source = $this->mSource;
            
            $source = RemoveComments( $source );
            
            $ret = '';
            for ( $i = 0; $i < strlen( $source ); ++$i ) {
                $c = $source{ $i };
                switch ( $c ) {
                    case '&':
                        // grab a few characters ahead so that we can determine if it's a valid entity
                        // or if we need to escape it
                        $entity = substr( $source, $i, 10 );
                        $entityend = strpos( $source, ';' );
                        if ( $entityend === true ) {
                            $literal = XHTMLSanitizer_GetEntity( $entity );
                            if ( $literal !== false ) {
                                $ret .= $c;
                                break;
                            }
                        }
                        // else...
                        // ; symbol not found
                        // or invalid entity
                        $water->Notice( 'XHTMLSanitizer escaping entity', $entity );
                        $ret .= '&amp;';
                        break;
                    case '<':
                    case '>':
                        break;
                    default:
                        $ret .= $c;
                }
            }
            
            return $ret;
        }
        public function AllowTag( XHTMLSaneTag $tag ) {
        }
    }
    
    class XHTMLSaneTag {
        public function XHTMLSaneTag( $tagname ) {
            w_assert( is_string( $tagname ) );
            w_assert( preg_match( '#^[a-z0-9]+$#', $tagname ) );
        }
        public function AllowAttribute( XHTMLSaneAttribute $attribute ) {
        }
    }
    
    class XHTMLSaneAttribute {
        public function XHTMLSaneAttribute( $attributename ) {
            w_assert( preg_match( '#^[a-z]+$#', $attributename ) );
        }
    }
    
?>
