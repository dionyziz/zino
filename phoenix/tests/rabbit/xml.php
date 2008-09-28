<?php
    class TestXML extends Testcase {
        protected $mAppliesTo = 'libs/rabbit/xml';

        public function TestParse() {
            $parser = New XMLParser(
                '<?xml version="1.0"?>
                 <garden name="flowers">
                    <flower type="rose" />
                    <flower type="violet" />
                    Roses are red &amp;
                    violets are blue;
                    You are so sweet,
                    and I love you too.
                 </garden>'
            );
            $root = $parser->Parse();
            $this->Assert( $root instanceof XMLNode, 'XML Parser did not return an XML root node' ); 
            $this->AssertEquals( 'garden', $root->nodeName, 'Root node name is invalid' );
            $this->AssertEquals( 1, count( $root->attributes ), 'Root node does not have one attribute as expected' );
            foreach ( $root->attributes as $name => $value ) {
                $this->AssertEquals( 'name', $name, 'Attribute name was not "name" as expected' );
                $this->AssertEquals( 'flowers', $value, 'Attribute value was not "flowers" as expected' );
            }
            $this->AssertEquals( 3, count( $root->childNodes ), 'Number of childNodes is invalid' );
            $i = 0;
            foreach ( $root->childNodes as $child ) {
                switch ( $i ) {
                    case 0:
                        $this->Assert( $child instanceof XMLNode );
                        break;
                    case 1:
                        $this->Assert( $child instanceof XMLNode );
                        break;
                    case 2:
                        $this->Assert( is_string( $child ) );
                        break;
                }
                ++$i;
            }
        }
    }

    return New TestXML();
?>
