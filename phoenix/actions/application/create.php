<?php
    function ActionApplicationCreate( tText $name, tText $description, tText $url, tText $logo ) {
        global $libs;
        global $user;
        
        $libs->Load( 'application' );
        
        $app = New Application();
        $app->Userid = $user->Id;
        $app->Name = $name->Get();
        $app->Description = $description->Get();
        $app->Url = $url->Get();
        $app->Logo = $logo->Get();
        
        $app->Save();
        
        return Redirect( "?p=applications" );
    }
?>