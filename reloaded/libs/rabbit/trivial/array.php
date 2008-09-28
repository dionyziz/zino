<?php
    if (!function_exists('array_intersect_key')) {
        function array_intersect_key( /* $array1 , $array2, $arrayN */ ) { /* stolen from http://gr2.php.net/manual/en/function.array-intersect-key.php#68179 */
            $arrs = func_get_args();
            $result = array_shift( $arrs );
            w_assert( is_array( $result ) );
            foreach ( $arrs as $array ) {
                w_assert( is_array( $array ) );
                foreach ( $result as $key => $v ) {
                    if ( !array_key_exists( $key, $array ) ) {
                        unset( $result[ $key ] );
                    }
                }
            }
            return $result;
        }
    }
?>
