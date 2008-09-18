<?php
    class ElementAdminlogView extends Element {
        public function Render( tInteger $offset ) {
            global $user;
            global $libs;
            global $page;
            
            $libs->Load( 'adminpanel/adminaction' );
            $page->setTitle( 'Logged admin actions' );
            
            if ( !$user->hasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?>Permission Denied<?php
                return;
            }
            
            ?><h2>Logged admin actions</h2><?php 
            
            $offset=$offset->Get();            
            if ( $offset < 0 ) $offset = 0;
            
            $adminFinder = new AdminActionFinder();
            $admins = $adminFinder->FindAll( $offset, 20 );   
            $numactions = $adminFinder->Count();  
            
            ?><p><?php
            for ( $i = 0; $i < $numactions; $i += 20 ) {
                ?><a href="?p=adminlog&amp;offset=<?php echo $i;?>"><?php echo $i;?> </a><?php
            }
            ?></p><?php
           
            ?><table>
                <tr>
                    <th>Admin username</th>
                    <th>IP</th>
                    <th>Action</th>
                    <th>Target</th>
                    <th>Id</th>
                    <th>When</th>
                </tr>
            <?php
            foreach ( $admins as $admin ) {
                ?><tr><td><?php
                echo $admin->name;
                ?></td><td><?php
                echo long2ip($admin->userip);
                ?></td><td><?php
                switch( $admin->action ) {
                    case 'delete':
                        ?>deleted<?php
                        break;
                    case 'edit':
                        ?>edited<?php
                        break;
                }
                ?></td><td><?php
                echo $admin->target;
                ?></td><td><?php
                echo $admin->targetid;
                ?></td><td><?php
                echo $admin->date;
                ?></td></tr><?php
            }     
            ?></table><?php
        }
    }
?>
