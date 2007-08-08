<?php
    final class Coala {
        public function Run( $type , $which , $req ) {
    		global $water;
    		
    		w_assert( strpos( $which , '..' ) == false );
    		w_assert( $type == 'warm' || $type == 'cold' );
            
    		$file = 'units/' . $which;
    		if ( $type == 'warm' ) {
    			$file .= '.do';
    		}
    		
    		ob_start();
    		Rabbit_Include( $file );
    		$output = ob_get_clean();
    		if ( strlen( $output ) ) {
                ?>alert('Coala unit should not output data on include (' + <?php
                echo w_json_encode( $output )
                ?> + '); coala call failed');<?php
        		$water->Notice( 'Coala unit should not output data on include; coala call failed' );
    			return;
    		}
    		$unitfunc = 'Unit' . str_replace( '/' , '' , $which );
    		if ( !function_exists( $unitfunc ) ) {
                ?>alert('Coala unit does not contain the appropriate function (' + <?php
                echo w_json_encode( $unitfunc );
                ?> + '); coala call failed');<?php
                $water->Notice( 'Coala unit does not contain the appropriate function; coala call failed' );
    			return;
    		}
            
    		ob_start();
    		Rabbit_TypeSafe_Call( $unitfunc , $req );
    		$js = ob_get_clean();
    		return $js;
        }
        public function ParseRequest( $warmable , $req ) {
            // parse a coala request and return an array of main elements
            global $user;
            
            if ( !isset( $req[ 'ids' ] ) ) {
                ?>alert( 'Coala error: Invalid call (need ids)' );<?php
                return;
            }
            
            $units = explode( ':' , $req[ 'ids' ] );
            
            $ret = array();
            $i = 0;
            foreach ( $units as $unit ) {
                $calltypesymbol = substr( $unit , 0 , 1 );
                switch ( $calltypesymbol ) {
                    case '~': // cold
                        $type = 'cold';
                        break;
                    case '!': // warm
                        $type = 'warm';
                        if ( !$warmable ) {
                            ?>alert( 'Attempt to invoke warm unit in cold call' );<?php
                            return;
                        }
                        break;
                    default:
                        ?>alert( 'Invalid unit type prefix (~ or ! unspecified?)' );<?php
                        return;
                }
                if ( !isset( $req[ "p$i" ] ) ) {
                    ?>alert( 'Attempt to invoke a unit without its parameter specified (<?php
                    echo "p$i";
                    ?>)' );<?php
                    return;
                }
                $parameters = $req[ "p$i" ];
                $args = explode( '&' , $parameters );
                $params = array();
                foreach ( $args as $keyvalue ) {
                    $split = explode( '=' , $keyvalue );
                    $key = urldecode( $split[ 0 ] );
                    $value = urldecode( $split[ 1 ] );
                    if ( is_numeric( $value ) ) { // cast
                        $value = ( float )$value;
                        if ( intval( $value ) == $value ) {
                            $value = ( integer )$value;
                        }
                    }
                    $params[ $key ] = $value;
                }
                $callid = substr( $unit , 1 );
                $callid = str_replace( '..' , '' , $callid ); // only allow units inside /units
                $ret[] = array(
                    'type' => $type,
                    'id'   => $callid,
                    'req'  => $params
                );
                ++$i;
            }
            
            return $ret;
        }
    }
    
    return New Coala(); // singleton
?>
