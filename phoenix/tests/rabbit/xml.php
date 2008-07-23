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
        }
    }

    return New TestXML();
?>
