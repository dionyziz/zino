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
            $page->SetTitle( 'Ενέργειες διαχειριστών' );
            
            if ( !$user->hasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
                ?>Permission Denied<?php
                return;
            }
            
            ?><h2>Ενέργειες διαχειριστών</h2><?php 
            
            $adminFinder = new AdminActionFinder();
            $offset = ( $pageno - 1 ) * $limit;
            $admins = $adminFinder->FindAll( $offset, 20 );   
            
            ?><table class="stats">
                <tr>
                    <th>Διαχειριστής</th>
                    <th>IP</th>
                    <th>Ενέργεια</th>
                    <th>Πότε</th>
                    <th>Τύπος</th>
                    <th class="numeric">Id</th>
                </tr>
            <?php
            foreach ( $admins as $admin ) {
                ?><tr<?php
                switch ( $admin->Action ) {
                    case 'delete':
                        ?> class="deleted"<?php
                        break;
                    case 'edit':
                        ?> class="edited"<?php
                        break;
                }
                ?>><td><a href="<?php
                Element( 'user/url', $admin->User->Id, $admin->User->Subdomain );
                ?>"><?php
                echo $admin->User->Name;
                ?></a></td><td><?php
                echo long2ip( $admin->userip );
                ?></td><td><?php
                switch ( $admin->Action ) {
                    case 'delete':
                        ?>Διαγραφή<?php
                        break;
                    case 'edit':
                        ?>Επεξεργασία<?php
                        break;
                }
                ?></td><td><?php
                Element( 'date/diff', $admin->Date );
                ?></td><td><?php
                echo $admin->Target;
                ?></td><td class="numeric"><a href="<?php
                ob_start();
                Element( 'url', $admin->Item );
                echo htmlspecialchars( ob_get_clean() );
                echo $admin->Targetid;
                ?></td></tr><?php
            }     
            ?></table><br /><?php
            $numactions = $adminFinder->Count();  
            $totalpages = ceil( $numactions / $limit );
            Element( 'pagify', $pageno, "?p=adminlog&pageno=", $totalpages );
            ?><br /><?php
        }
    }
?>
