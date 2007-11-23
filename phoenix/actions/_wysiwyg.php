<?php
    function ActionWYSIWYG( tString $lookatme ) {
        global $libs;
        
        $libs->Load( 'sanitizer' );

        $sanitizer = New XHTMLSanitizer();
        
        $tags = array(
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
        );

        foreach ( $tags as $key => $value ) {
            if ( $key === "" ) {
                continue;
            }
            if ( is_string( $value ) ) {
                $rule = New XHTMLSaneTag( $value );
                foreach ( $tags[ '' ] as $attribute ) {
                    $rule->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
                }
            }
            else {
                $rule = New XHTMLSaneTag( $key );
                foreach ( $value as $attribute ) {
                    $rule->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
                }
                foreach ( $tags[ '' ] as $attribute ) {
                    $rule->AllowAttribute( New XHTMLSaneAttribute( $attribute ) );
                }
            }
            $sanitizer->AllowTag( $rule );
        }

        $sanitizer->SetSource( $lookatme->Get() );
        
        header( 'Content-type: text/plain' );
        echo $sanitizer->GetXHTML();
        die();
    }
?>
