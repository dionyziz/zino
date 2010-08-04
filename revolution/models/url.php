<?php

    function isBetween( $character, $begin, $end ) {
        return strcmp( $character, $begin ) >= 0 && strcmp( $character, $end ) <= 0;
    }

    // return a url that is unique for this userid, according to function
    function URL_FormatUnique( $string, $userid, $function ) {
        $formatted = URL_Format( $string );
        while ( is_array( call_user_func( $function, $formatted, $userid ) ) ) {
            $formatted = $formatted . '_';
        } 
        return $formatted;
    }

    function URL_Format( $string ) {
        static $greekToLatin = array(
            'α' => 'a',
            'β' => 'b',
            'γ' => 'g',
            'δ' => 'd',
            'ε' => 'e',
            'ζ' => 'z',
            'η' => 'i',
            'θ' => 'th',
            'ι' => 'i',
            'κ' => 'k',
            'λ' => 'l',
            'μ' => 'm',
            'ν' => 'n',
            'ξ' => 'ks',
            'ο' => 'o',
            'π' => 'p',
            'ρ' => 'r',
            'σ' => 's',
            'τ' => 't',
            'υ' => 'u',
            'φ' => 'f',
            'χ' => 'x',
            'ψ' => 'ps',
            'ω' => 'w',
            'ά' => 'a',
            'έ' => 'e',
            'ή' => 'i',
            'ί' => 'i',
            'ό' => 'o',
            'ύ' => 'u',
            'ώ' => 'w',
            'Α' => 'A',
            'Β' => 'B',
            'Γ' => 'G',
            'Δ' => 'D',
            'Ε' => 'E',
            'Ζ' => 'Z',
            'Η' => 'I',
            'Θ' => 'Th',
            'Ι' => 'I',
            'Κ' => 'K',
            'Λ' => 'L',
            'Μ' => 'M',
            'Ν' => 'N',
            'Ξ' => 'Ks',
            'Ο' => 'O',
            'Π' => 'P',
            'Ρ' => 'R',
            'Σ' => 'S',
            'Τ' => 'T',
            'Υ' => 'Y',
            'Φ' => 'F',
            'Χ' => 'X',
            'Ψ' => 'Ps',
            'Ω' => 'W',
            'Ά' => 'A',
            'Έ' => 'E',
            'Ή' => 'I',
            'Ί' => 'I',
            'Ό' => 'O',
            'Ύ' => 'Y',
            'Ώ' => 'W',
            'ς' => 's'
        );
        static $punctuation = array();
        $new = '';
        $putUnderscore = false;
        for ( $i = 0; $i < mb_strlen( $string, "UTF-8" ); ++$i ) {
            $c = mb_substr( $string, $i, 1, "UTF-8" );
            if ( isBetween( $c, 'A', 'Z' ) ||
                isBetween( $c, 'a', 'z' ) ||
                isBetween( $c, '0', '9' ) ||
                in_array( $c, $punctuation ) ) {
                $new .= $c;
                $putUnderscore = true;
            }
            else if ( isset( $greekToLatin[ $c ] ) ) {
                $new .= $greekToLatin[ $c ];
                $putUnderscore = true;
            }
            else if ( $putUnderscore ) {
                $new .= '_';
                $putUnderscore = false;
            }
        }
        $new = rtrim( $new, '_' );
        return $new ? $new : '_';
    }

?>
