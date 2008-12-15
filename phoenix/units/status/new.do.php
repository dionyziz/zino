<?php
    function UnitStatusNew( tString $message ) {
        global $user;
        
        $message = $message->Get();
        if ( $user->Exists() ) {
            $status = New StatusBox();
            $status->Message = $message;
            $status->Save();
        }
    }
?>
