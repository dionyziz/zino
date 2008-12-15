<?php
    function UnitStatusNew( tString $message ) {
        global $user;
        global $libs;
        
        $message = $message->Get();
        if ( $user->Exists() ) {
            $libs->Load( 'user/statusbox' );
            
            $status = New StatusBox();
            $status->Message = $message;
            $status->Save();
        }
    }
?>
