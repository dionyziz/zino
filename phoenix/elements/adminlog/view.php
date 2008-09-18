<?php
    class ElementAdminlogView extends Element {
        public function Render( tInteger $pageno ) {
            global $user;
            global $libs;
            global $page;
            
            $limit = 20;
            $pageno = $pageno->Get();
            if ( $pageno < 1 ) {
                $pageno = 1;
            }
            $libs->Load( 'adminpanel/adminaction' );
            $page->SetTitle( 'Logged admin actions' );
            
            if ( !$user->hasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?>Permission Denied<?php
                return;
            }
            
            ?><h2>Logged admin actions</h2><?php 
            
            $adminFinder = new AdminActionFinder();
            $offset = ( $pageno - 1 ) * $limit;
            $admins = $adminFinder->FindAll( $offset, 20 );   
            
            ?><table class="stats">
                <tr>
                    <th>Admin username</th>
                    <th>IP</th>
                    <th>Action</th>
                    <th>Target</th>
                    <th class="numeric">Id</th>
                    <th>When</th>
                </tr>
            <?php
            foreach ( $admins as $admin ) {
                ?><tr<?php
                switch ( $admin->action ) {
                    case 'deleted':
                        ?> class="deleted"<?php
                        break;
                    case 'edited':
                        ?> class="edited"<?php
                        break;
                }
                ?>><td><?php
                echo $admin->name;
                ?></td><td><?php
                echo long2ip( $admin->userip );
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
                ?></td><td class="numeric"><?php
                echo $admin->targetid;
                ?></td><td><?php
                echo $admin->date;
                ?></td></tr><?php
            }     
            ?></table><?php
            $numactions = $adminFinder->Count();  
            $totalpages = ceil( $numactions / $limit );
            Element( 'pagify', $pageno, "?p=adminlog&pageno=", $totalpages );
        }
    }
?>
