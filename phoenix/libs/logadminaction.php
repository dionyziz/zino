<?php
    function AdminAction_Log ( $userid , $userip , $actiontype , $targettype , $targetid ) {   //lib that makes the log action query
        global $db;
        
        switch ( $actiontype ) {
            case "delete":
                $actiontype = 1;
                break;
            case "edit":
                $actiontype = 2;
                break;
            default:
                return;
        }
        
        switch ( $targettype ) {
            case "comment":
                $targettype = 1;
                break;
            case "poll":
                $targettype = 2;
                break;
            case "journal":
                $targettype = 3;
                break;
            case "image":
                $targettype = 4;
                break;
            default:
                return;
        }
        
        $query = $db->Prepare( 
                "INSERT INTO `adminactions` VALUES ( ' ' , :user_id , :user_ip , NOW() , " . $actiontype . " , " . $targettype . " , :target_id )" 
        );
        //$query->BindTable( 'adminactions' );
        $query->Bind( 'user_id' , $userid );
        $query->Bind( 'user_ip' , $userip );
        $query->Bind( 'target_id' , $targetid );
        
        $query->Execute();    
    }
?>
