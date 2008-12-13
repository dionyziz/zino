<?php
    function ActionContactsInvite( tBooleanArray $approved ) {
        global $libs;
        
        $approved = $approved->Get();
        
        $str = count( $approved );
        foreach ( $approved as $sampe ) {
            $str = $str . (int)$sample;
        }
           
        return Redirect( '?p=contactfinder&email='.$str );
    }
?>
