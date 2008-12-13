<?php
    function ActionContactsInvite( tTextArray $approved ) {
        global $libs;
        
        $str = count( $approved );
        foreach ( $approved as $sampe ) {
            $email = $sample->Get();
            $str = $str . $email;
        }
           
        return Redirect( '?p=contactfinder&email='.$str );
    }
?>
