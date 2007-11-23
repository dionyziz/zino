<?php
    function ElementDeveloperDionyzizXMLParse() {
        $parser = xml_parser_create( 'UTF-8' );
        
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 0 );

        function HandleCharData( $parser, $data ) {
            echo 'Got character data: ';
            var_dump( $data );
            echo "\n";
        }

        xml_set_character_data_handler( $parser, 'HandleCharData' );

        xml_parse(
            $parser,
            "<?xml version=\"1.0\"?>" .
            "<document>" .
            "<strong>hello</strong> <strong>world</strong>" .
            "</document>"
        );

        xml_parser_free( $parser );
    }
?>
