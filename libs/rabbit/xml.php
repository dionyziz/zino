<?php

class XMLNode {
    private $mChildNodes; // array of XMLNodes or strings
    private $mAttributes; // array key => value
    public $parentNode;
    public $nodeName;
    
    public function XMLNode( $name ) {
        $this->nodeName = $name;
        $this->parentNode = false;
        $this->mChildNodes = array();
        $this->mAttributes = array();
    }
    public function appendChild( $child ) {
        w_assert( is_string( $child ) || $child instanceof XMLNode );
        $this->mChildNodes[] = $child;
    }
    public function firstChild() {
        if ( count( $this->mChildNodes ) ) {
            return $this->mChildNodes[ 0 ];
        }
        return false;
    }
    public function lastChild() {
        if ( count( $this->mChildNodes ) ) {
            return $this->mChildNodes[ count( $this->mChildNodes ) - 1 ];
        }
        return false;
    }
    public function getElementsByTagName( $name ) { // only direct children! (unlike DOM)
        $ret = array();
        foreach ( $this->mChildNodes as $child ) {
            if ( $child->nodeName == $name ) {
                $ret[] =& $child;
            }
        }
        return $ret;
    }
    public function setAttribute( $name, $value ) {
        $this->attributes[ $name ] = $value;
    }
    public function attribute( $name ) {
        if ( isset( $this->attributes[ $name ] ) ) {
            return $this->attributes[ $name ];
        }
        return false;
    }
    public function innerHTML() {
        global $water;
        
        $ret = '';
        foreach ( $this->mChildNodes as $xmlnode ) {
            if ( is_string( $xmlnode ) ) {
                $ret .= $xmlnode;
            }
            else {
                $ret .= $xmlnode->outerHTML();
            }
        }
        $water->Trace( 'Serialized tag: ' . $this->nodeName, $ret );
        return $ret;
    }
    public function outerHTML() {
        $ret = '<' . $this->nodeName;
        
        $attributes = array();
        foreach ( $this->mAttributes as $attribute => $value ) {
            $attributes[] = $attribute . '="' . htmlspecialchars( $value ) . '"';
        }
        
        if ( !empty( $attributes ) ) {
            $ret .= ' ' . implode( ' ', $attributes );
        }
        
        if ( empty( $this->mChildNodes ) ) {
            $ret .= '/>';
        }
        else {
            $ret .= '>';
            $ret .= $this->innerHTML();
            $ret .= '</' . $this->nodeName . '>';
        }
        
        return $ret;
    }
}

class XMLParser {
    private $mDepth;
    private $mNodesQueue; /* array of XMLNode/string */
    private $mLastNode;
    private $mXML;
    private $mError;
    private $mNativeParser;
    private $mIgnoreEmptyTextNodes;
    
    public function ignoreEmptyTextNodes( $preference ) {
        w_assert( is_bool( $preference ) );
        $this->mIgnoreEmptyTextNodes = $preference;
    }
    public function parseElementStart( $parser, $name, $attribs ) {
        $newnode = New XMLNode( $name );
        foreach ( $attribs as $attribute => $value ) {
            $newnode->setAttribute( $attribute, $value );
        }
        if ( count( $this->mNodesQueue ) ) {
            $current = $this->mNodesQueue[ count( $this->mNodesQueue ) - 1 ];
            $newnode->parentNode = $current;
            $current->appendChild( $newnode );
        }
        $this->mNodesQueue[] = $newnode; // push
    }
    public function parseElementEnd( $parser, $name ) {
        $this->mLastNode = array_pop( $this->mNodesQueue );
    }
    public function parseText( $parser, $string ) {
        if ( $this->mIgnoreEmptyTextNodes && trim( $string ) == '' ) {
            return;
        }
        if ( !count( $this->mNodesQueue ) ) {
            $this->mError = 'Text node cannot be root node';
        }
        $current = $this->mNodesQueue[ count( $this->mNodesQueue ) - 1 ];
        $current->appendChild( $string );
    }
    public function XMLParser( $xml ) {
        $this->mXML = $xml;
        $this->mNodesQueue = array();
        $this->mError = false;
        $this->mLastNode = false;
        $this->mIgnoreEmptyTextNodes = true;
    }
    public function Parse() {
        global $water;
        
        $this->mNativeParser = xml_parser_create();
        xml_parser_set_option(
            $this->mNativeParser,
            XML_OPTION_CASE_FOLDING,
            0
        );
        xml_set_element_handler(
            $this->mNativeParser, 
            array( $this, 'parseElementStart' ),
            array( $this, 'parseElementEnd' )
        );
        xml_set_character_data_handler(
            $this->mNativeParser,
            array( $this, 'parseText' )
        );
        $success = xml_parse( $this->mNativeParser, $this->mXML );
        if ( !$success ) {
            $this->mError = xml_error_string(
                                xml_get_error_code( $this->mNativeParser )
                            ) 
                            . ' at line ' 
                            . xml_get_current_line_number(
                                $this->mNativeParser
                            );
        }
        xml_parser_free( $this->mNativeParser );
        
        if ( !empty( $this->mError ) ) {
            $water->Notice( 'XML Parsing Failed: ' . $this->mError );
            return false;
        }
        if ( $this->mLastNode === false ) {
            $water->Notice( 'XML Parsing Failed: No root node specified', $this->mXML );
            return false;
        }
        $water->Trace('Parsed XML', $this->mXML);
        
        return $this->mLastNode; // return root node (or false if none)
    }
}

?>
