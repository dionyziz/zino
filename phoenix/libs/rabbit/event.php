<?php
    function FireEvent( /* $event, $arg1, $arg2, ... */ ) {
        global $libs;
        
        $libs->Load( 'comet' );
        
        $args = func_get_args();
        w_assert( count( $args ), 'No event arguments given' );
        $event = array_shift( $args );
        
        if ( !function_exists( 'Project_Events' ) ) {
            return;
        }
        $projectevents = Project_Events();
        if ( !isset( $projectevents[ $event ] ) ) {
            return;
        }
        
        $which = $projectevents[ $event ];
        
        if ( is_string( $which ) ) {
            $plasma = array( $which );
        }
        else {
            w_assert( is_array( $which ), 'Event target must be a string or an array of strings of plasma unit paths' );
            $plasma = $which;
        }
        
        foreach ( $plasma as $which ) {
            PropagateToPlasma( $which, $args );
        }
    }
    
    function PropagateToPlasma( $which, $args ) {
        global $water;
        
        $file = 'units/' . $which . '.plasma';
        
        ob_start();
        Rabbit_Include( $file );
        $output = ob_get_clean();
        
        if ( strlen( $output ) ) {
            ?>alert('Plasma unit should not output data on include (' + <?php
            echo w_json_encode( $output )
            ?> + '); plasma call failed');<?php
            $water->Notice( 'Plasma unit should not output data on include; plasma call failed' );
            return;
        }
        
        $plain = str_replace( '/' , '' , $which );
        $unitfunc = 'Unit' . $plain;
        if ( !function_exists( $unitfunc ) ) {
            ?>alert('Plasma unit does not contain the appropriate function (' + <?php
            echo w_json_encode( $unitfunc );
            ?> + '); plasma call failed');<?php
            $water->Notice( 'Plasma unit does not contain the appropriate function; plasma call failed' );
            return;
        }
        
        ob_start();
        $ret = call_user_func_array( $unitfunc, $args );
        $js = ob_get_clean();

        $channelparts = explode( '/', $which );
        foreach ( $channelparts as $i => $value ) {
            $channelparts[ $i ] = ucfirst( $value );
        }
        $channel = implode( '', $channelparts );
        
        if ( $ret !== false ) {
            if ( is_int( $ret ) || is_string( $ret ) ) {
                $channels = array(
                    $channel . ( string )$ret
                );
            }
            else if ( is_array( $ret ) ) {
                $channels = array();
                foreach ( $ret as $specificity ) {
                    w_assert( is_int( $specificity ) || is_string( $specificity ), 'Plasma specificity must be an array of strings and integers' );
                    $channels[] = $channel . ( string )$specificity;
                }
            }
            else { // no specificity
                $channels = array( $channel );
            }
            
            foreach ( $channels as $channel ) {
                die( 'Publishing on channel: ' . $channel . "\n\n" . $js );
                Comet_Publish( $channel, $js );
            }
        }
    }
?>
