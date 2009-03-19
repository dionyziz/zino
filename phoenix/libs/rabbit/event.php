<?php
    function FireEvent( /* $event, $arg1, $arg2, ... */ ) {
        global $water;
        global $libs;
        
        $libs->Load( 'comet' );
        
        $args = func_get_args();
        w_assert( count( $args ), 'No event arguments given' );
        $event = array_shift( $args );
        
        if ( !function_exists( 'Project_Events' ) ) {
            die( 'Project_Events not found' );
            return;
        }
        $projectevents = Project_Events();
        if ( !isset( $projectevents[ $event ] ) ) {
            die( 'Requested event not bound' );
            return;
        }
        
        $which = $projectevents[ $event ];
        w_assert( is_string( $which ), 'Event target must be the string path of the plasma unit' );
        
        $file = 'units/' . $which . '.plasma';
        
        die( 'About to load unit ' . $file );

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
        call_user_func_array( $unitfunc, $args );
        $js = ob_get_clean();
        Comet_Publish( $plain, $js );
    }
?>
