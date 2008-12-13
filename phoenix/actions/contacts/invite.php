<?php
    function ActionContactsInvite( tTextArray $approved ) {
        global $libs;
        
        $str = count( $approved );
        foreach ( $approved as $sample ) {
            $sample = $sample->Get();
            $str = $str . $sample;
        }
           
        return Redirect( '?p=contactfinder&email='.$str );
    }
?>
