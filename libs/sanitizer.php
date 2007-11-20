<?php
    include 'rabbit/xml.php';
    
    global $xhtmlsanitizer_goodtags;
    
    $xhtmlsanitizer_goodtags = XHTMLSanitizer_DecodeTags( array(
        'a' => array( 'coords', 'href', 'hreflang', 'name', 'rel', 'rev', 'shape', 'target', 'type' ), 
        'abbr', 'acronym', 'address',
        'area' => array( 'coords', 'href', 'nohref', 'shape', 'target' ), 
        'b', 'bdo', 'big', 
        'blockquote' => array( 'cite' ), 
        'br', 
        'button' => array( 'disabled', 'type', 'value' ), 
        'caption', 'cite', 'code', 
        'col' => array( 'span' ),
        'colgroup' => array( 'span' ),
        'dd', 'del', 'div', 'dfn', 'dl', 'dt', 'em', 'fieldset', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'hr', 'i',
        'img' => array( 'src', 'alt', 'border', 'height', 'ismap', 'longdesc', 'usemap', 'vspace', 'width' ), 
        'ins' => array( 'cite', 'datetime' ),
        'kdb', 'label', 'legend',
        'li' => array( 'type', 'value' ),
        'map' => array( 'map' ),
        'noframes', 'noscript', 'ol', 'optgroup', 'option', 'p', 
        'q' => array( 'cite' ),
        'samp', 'small', 'span', 'strong', 'sub', 'sup', 
        'table' => array( 'cellpadding', 'cellspacing', 'rules', 'summary' ),
        'tbody', 
        'td' => array( 'abbr', 'colspan', 'rowspan' ),
        'textarea' => array( 'cols', 'rows' ), 'tfoot', 
        'th' => array( 'scope', 'colspan', 'colspan' ),
        'thead', 'tr', 'tt', 
        'ul' => array( 'compact', 'type' ),
        '' => array( 'title', 'lang', 'dir', 'accesskey', 'tabindex' ) // everywhere
    ) );
    
    function XHTMLSanitizer_DecodeTags( $tags ) {
        $ret = array();
        foreach ( $tags as $key => $value ) {
            if ( is_string( $value ) ) {
                $ret[ $value ] = true;
            }
            else if ( is_array( $value ) ) {
                $ret[ $key ] = array();
                foreach ( $value as $attribute ) {
                    $ret[ $key ][ $attribute ] = true;
                }
            }
        }
        return $ret;
    }
    
    class XHTMLSanitizer {
        private $mSource;
        private $mAllowedTags;
        
        public function XHTMLSanitizer() {
            $this->mAllowedTags = array();
            $this->mSource = false;
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
        private function RemoveComments( $htmlsource ) {
            global $water;
            
            if ( preg_match( '#\<\!--(.*?)\<\!--(.*?)--\>#', $htmlsource ) ) {
                $water->Warning( 'XHTMLSanitizer: Call me paranoid, but finding \'<!--\' inside this comment makes me suspicious' );
            }
            return preg_replace( '#\<\!--(.*?)--\>#', '', $htmlsource );
        }
        private function ReduceWhitespace( $htmlsource ) {
            return preg_replace( "#([ \t\n\r]+)#", ' ', $htmlsource );
        }
        public function GetXHTML() {
            global $water;
            
            w_assert( $this->mSource !== false, 'Please SetSource() before calling GetXHTML()' );
            
            $tags = array();
            
            $source = $this->mSource;
            
            $source = $this->RemoveComments( $source );
            
            $descriptors = array(
                0 => array( "pipe", "r" ),
                1 => array( "pipe", "w" ),
            );
            
            $process = proc_open( '/var/www/chit-chat.gr/beta/bin/sanitizer/sanitize', $descriptors, $pipes, '.', array() );
            
            if ( !is_resource( $process ) ) {
                $water->Notice( 'Failed to start the sanitizer' );
                return '';
            }
            
            fwrite( $pipes[ 0 ], $source );
            fclose( $pipes[ 0 ] );
            
            $ret = stream_get_contents( $pipes[ 1 ] );
            
            ob_start();
            var_dump( $ret );
            $tidied = ob_get_clean();
            
            $water->Trace( 'Sanitizer tidied up document', $tidied );
            
            fclose( $pipes[ 1 ] );
            
            $returnvalue = proc_close( $process );
            
            $water->Trace( 'Sanitizer exited with status ' . $returnvalue );
            
            $parser = New XMLParser( '<body>' . trim( $this->ReduceWhitespace( $ret ) ) . '</body>' );
            $body = $parser->Parse();
            if ( $body === false ) {
                return '';
            }
            w_assert( $body->nodeName == 'body' );
            
            $ret = $this->XMLInnerHTML( $body );
            
            return $ret;
        }
        public function SanitizeURL( $url ) {
            static $validprotocols = array(
                'http', 'ftp', 'https', 'mailto', 'irc'
            );
            
            if ( strpos( $url, ':' ) !== false ) {
                $safe = false;
                foreach ( $validprotocols as $protocol ) {
                    if ( substr( $url, 0, strlen( $protocol ) ) == $protocol ) {
                        $safe = true;
                        break;
                    }
                }
                if ( !$safe ) {
                    return false;
                }
            }
            
            return $url;
        }
        private function XMLOuterHTML( XMLNode $root ) {
            if ( !isset( $this->mAllowedTags[ $root->nodeName ] ) ) {
                return $this->XMLInnerHTML( $root );
            }
            
            $tagrule = $this->mAllowedTags[ $root->nodeName ];
            
            $ret = '<' . $root->nodeName;
            
            $attributes = array();
            foreach ( $root->attributes as $attribute => $value ) {
                if ( $tagrule->AttributeAllowed( $attribute ) ) {
                    if ( !empty( $value ) || ( $root->nodeName == 'img' && $attribute == 'alt' ) ) {
                        if ( $attribute == 'href' || $attribute == 'src' ) {
                            $value = $this->SanitizeURL( $value );
                            if ( empty( $value ) ) {
                                continue;
                            }
                        }
                        $attributes[] = $attribute . '="' . htmlentities( $value, ENT_QUOTES, 'UTF-8' ) . '"';
                    }
                }
            }
            
            if ( !empty( $attributes ) ) {
                $ret .= ' ' . implode( ' ', $attributes );
            }
            
            if ( empty( $root->childNodes ) ) {
                $ret .= '/>';
            }
            else {
                $ret .= '>';
                $ret .= $this->XMLInnerHTML( $root );
                $ret .= '</' . $root->nodeName . '>';
            }
            
            return $ret;
        }
        private function XMLInnerHTML( XMLNode $root ) {
            $ret = '';
            foreach ( $root->childNodes as $xmlnode ) {
                if ( is_string( $xmlnode ) ) {
                    $ret .= htmlentities( $xmlnode, ENT_QUOTES, 'UTF-8' );
                }
                else {
                    $ret .= $this->XMLOuterHTML( $xmlnode );
                }
            }
            return $ret;
        }
        public function AllowTag( XHTMLSaneTag $tag ) {
            global $water;
            global $xhtmlsanitizer_goodtags;
            
            if ( !isset( $xhtmlsanitizer_goodtags[ $tag->Name() ] ) ) {
                $water->Notice( 'XHTMLSanitizer tag "' . $tag->Name() . '" is not safe or valid' );
                return;
            }

            $this->mAllowedTags[ $tag->Name() ] = $tag;
        }
    }
    
    class XHTMLSaneTag {
        private $mName;
        private $mAllowedAttributes;
        
        public function Name() {
            return $this->mName;
        }
        public function AttributeAllowed( $attributename ) {
            w_assert( is_string( $attributename ) );
            return isset( $this->mAllowedAttributes[ $attributename ] );
        }
        public function XHTMLSaneTag( $tagname ) {
            w_assert( is_string( $tagname ) );
            w_assert( preg_match( '#^[a-z0-9]+$#', $tagname ) );
            $this->mName = $tagname;
        }
        public function AllowAttribute( XHTMLSaneAttribute $attribute ) {
            global $water;
            global $xhtmlsanitizer_goodtags;
            
            if ( !isset( $xhtmlsanitizer_goodtags[ $this->mName ][ $attribute->Name() ] ) && !isset( $xhtmlsanitizer_goodtags[ '' ][ $attribute->Name() ] ) ) {
                $water->Notice( 'XHTMLSanitizer attribute "' . $attribute->Name() . '" is not safe or valid for tag "' . $this->mName . '"' );
                return;
            }
            
            $this->mAllowedAttributes[ $attribute->Name() ] = $attribute;
        }
    }
    
    class XHTMLSaneAttribute {
        private $mName;
        
        public function Name() {
            return $this->mName;
        }
        public function XHTMLSaneAttribute( $attributename ) {
            w_assert( is_string( $attributename ) );
            w_assert( preg_match( '#^[a-z]+$#', $attributename ) );
            $this->mName = $attributename;
        }
    }
?>
