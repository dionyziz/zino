<?php
    final class Actions { // do-and-redirect controllers handler
        public function Request( $p , $req ) {
            global $water;
            
            if (   !isset( $p ) 
                || !preg_match( '#^[a-zA-Z0-9/]+$#' , $p ) ) {
                $water->Notice( 'Action specified is not valid or does not exist: ' . $p );
                return Redirect();
            }
            
            $action = $p;
            $actionfunction = 'Action' . str_replace( '/' , '' , $action );
            
            // no parentfolder check necessary because of regex
            Rabbit_Include( 'actions/' . $action );
            
            if ( !function_exists( $actionfunction ) ) {
                throw New Exception( 'Action is not functional', $actionfunction );
            }
            
            return Rabbit_TypeSafe_Call( $actionfunction, $req );
        }
    }
    
    return New Actions(); // singleton
?>
