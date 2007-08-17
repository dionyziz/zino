<?php

class Lib_XML__Node {
    private $mNodeName;
    private $mChildNodes; // array of Lib_XML__Nodes or strings
    private $mAttributes; // array key => value
    public $parentNode;
    
    public function Lib_XML__Node( $name ) {
        $this->nodeName = $name;
        $this->parentNode = false;
        $this->childNodes = array();
    }
    public function appendChild( &$child ) {
        w_assert( is_string( $child ) || $child instanceof Lib_XML__Node );
        $this->childNodes[] = $child;
    }
    public function firstChild() {
        if ( count( $this->childNodes ) ) {
            return $this->childNodes[ 0 ];
        }
        return false;
    }
    public function lastChild() {
        if ( count( $this->childNodes ) ) {
            return $this->childNodes[ count( $this->childNodes ) - 1 ];
        }
        return false;
    }
    public function getElementsByTagName( $name ) { // only direct children! (unlike DOM)
        $ret = array();
        foreach ( $this->childNodes as $child ) {
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
}

class Lib_XML__Parser {
    private $mDepth;
    private $mNodesQueue; /* array of Lib_XML__Node/strin */
    private $mLastNode;
    private $mXML;
    private $mError;
    private $mNativeParser;
    private $mIgnoreEmptyTextNodes;
    
    public function ignoreEmptyTextNodes( $preference) {
        w_assert( is_bool( $preference ) );
        $this->mIgnoreEmptyTextNodes = $preference;
    }
    public function parseElementStart( $parser, $name, $attribs ) {
        $newnode = New Lib_XML__Node( $name );
        foreach ( $attribs as $attribute => $value ) {
            $newnode->setAttribute( $attribute, $value );
        }
        if ( count( $this->mNodesQueue ) ) {
            $current =& $this->mNodesQueue[ count( $this->mNodesQueue ) - 1 ];
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
    public function Lib_XML__Parser( $xml ) {
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
        w_trace('Parsed XML', $this->mXML);
        
        return $this->mLastNode; // return root node (or false if none)
    }
}

?>
