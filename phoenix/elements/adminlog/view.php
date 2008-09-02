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
            for( $i=0 ; $i < $numactions ; $i += 20 ) {
            ?><a href="?p=adminlog&amp;offset=<?php echo $i;?>"><?php echo $i;?> </a><?php
            }
            ?></p><?php       
           
            foreach ( $admins as $admin ) {
                ?><p>User <?php
                echo $admin->name;
                ?> with ip <?php
                echo long2ip($admin->userip);
                ?> <?php
                switch( $admin->action ) {
                    case 'delete':
                        echo 'deleted';
                        break;
                    case 'edit':
                        echo 'edited';
                        break;
                    }
                ?> <?php
                echo $admin->target;
                    ?> with id <?
                echo $admin->targetid;
                ?> at <?php
                echo $admin->date;
                ?>.</p><?php
            }     
            return;
        }
    }
?>
