<?php
    /*
        Developer:Pagio
    */
    
    function GetCommentFrequency() {
        global $db;
        
        $sql = $db->Prepare(
            'SELECT COUNT( * ) /600 as `frequency`
            FROM `comments`
            WHERE `comment_created` + INTERVAL 1 HOUR + INTERVAL 10
            MINUTE > NOW( )
            '
        );
        $sql->BindTable( 'bannedusers' );
        $res = $sql->Execute();
        while ( $row = $res->FetchArray() ) {
            $freq = $row[ 'frequency' ];
        }
        return $freq;
    }
?>
