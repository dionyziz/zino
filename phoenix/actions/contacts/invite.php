<?php
    function ActionContactsInvite( tBooleanArray $approved ) {
        global $libs;
        
        die(var_dump($approved));
        
        $str = count( $approved );
        foreach ( $approved as $sampe ) {
            $str = $str . (int)$sample;
        }
           
        return Redirect( '?p=contactfinder&email='.$str );
    }
?>
