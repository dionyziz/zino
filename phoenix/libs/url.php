<?php

    function isBetween( $character, $begin, $end ) {
        return strcmp( $character, $begin ) >= 0 && strcmp( $character, $end ) <= 0;
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
            'Θ' => 'TH',
            'Ι' => 'I',
            'Κ' => 'K',
            'Λ' => 'L',
            'Μ' => 'M',
            'Ν' => 'N',
            'Ξ' => 'KS',
            'Ο' => 'O',
            'Π' => 'P',
            'Ρ' => 'R',
            'Σ' => 'S',
            'Τ' => 'T',
            'Υ' => 'Y',
            'Φ' => 'F',
            'Χ' => 'X',
            'Ψ' => 'PS',
            'Ω' => 'W',
            'Ά' => 'A',
            'Έ' => 'E',
            'Ή' => 'I',
            'Ί' => 'I',
            'Ό' => 'O',
            'Ύ' => 'Y',
            'Ώ' => 'W'
        );
        static $punctuation = array(
            '.',
            ',',
            ':',
            '-',
            '_',
            '(',
            ')',
            ';'
        );
        $new = '';
        $putUnderscore = false;
        for ( $i = 0; $i < strlen( $string ); ++$i ) {
            $c = $string[ $i ];
            if ( isBetween( $c, 'A', 'z' ) ||
                isBetween( $c, '0', '9' ) ||
                in_array( $c, $punctuation ) ) {
                $new .= $c;
                $putUnderscore = true;
            }
            else if ( isBetween( $c, 'Ά', 'ω' ) ) {
                $new .= $greekToLatin[ $c ];
                $putUnderscore = true;
            }
            else if ( $putUnderscore ) {
                $new .= '_';
                $putUnderscore = false;
            }
        }
        return rtrim( $new, '_' );
    }

?>
