<?php
    function ActionContactsInvite( tBooleanArray $approved ) {
        global $libs;
        
        $str = count( $approved );
        foreach ( $approved as $sampe ) {
            $sample = $sample->Get();
            $str = $str . (int)$sample;
        }
           
        return Redirect( '?p=contactfinder&email='.$str );
    }
?>
