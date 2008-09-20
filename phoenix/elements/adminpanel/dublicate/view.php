<?php
    class ElementAdminpanelDublicateView extends Element {
        public function Render() {
            global $libs;
            global $user;
            global $page;
            global $db;
                
            if ( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?> Permission Denied <?php
                return;
            }
            
            $page->setTitle( 'Dublicate accounts' );
            
            $query = $db->Prepare( "
                SELECT *
                FROM :loginattempts
                WHERE `login_username` != :username
                AND `login_ip`
                IN (
                    SELECT `login_ip`
                    FROM `loginattempts`
                    WHERE `login_username` = :username
                )
            ");
            $query->BindTable( 'loginattempts' );
            $query->Bind( 'username', 'pagio91' );
            $res = $query->Execute();
            
            foreach ( $res as $dub ) {
                ?><p><?php
                echo $dub->name;                
                ?></p><?php
            }
       }
    }
?>
