<?php
    function ActionContactsInvite( tTextArray $approved ) {
        global $libs;
        
        $str = count( $approved );
        foreach ( $approved as $sampe ) {
            $sample = $sample->Get();
            $str = $str . $sample;
        }
           
        return Redirect( '?p=contactfinder&email='.$str );
    }
?>
