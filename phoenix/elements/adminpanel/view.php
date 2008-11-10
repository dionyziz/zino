<?php    
    class ElementAdminpanelView extends Element {
        public function Render( tText $username, tText $pass ) {
	        global $page;
	        global $user;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Κεντρική σελίδα διαχειριστών' );
	        
	        ?><h2>Κεντρική σελίδα διαχειριστών</h2><?php
	        
	        ?><ul><?php
		        ?><li><a href="?p=statistics" >Στατιστικά στοιχεία του Zino</a></li><?php
		        ?><li><a href="?p=banlist" >Αποκλεισμένοι χρήστες</a></li><?php
		        ?><li><a href="?p=adminlog" >Ενέργειες διαχειριστών</a></li><?php
	        ?></ul><?php    
	        
	        
	        global $libs;	        
	        $libs->Load( 'contacts/contacts' );
	        $libs->Load( 'rabbit/helpers/email' );	        
	        
	        $username = $username->Get();
	        $pass = $pass->Get();
	        
	        $libs->Load( 'contacts/OpenInviter/openinviter' );  
      
              $inviter = new OpenInviter();
        $inviter->getPlugins();
        $inviter->startPlugin( 'hotmail' );
        $state = $inviter->login( $username, $pass );
        $inviter->logout();
        $inviter->stopPlugin();
        print_r( $state );    
        

	        $state = GetContacts( $username, $pass );
	        if( $state == false ) {
                ?><p>Failure...</p><?php                
            }
            else {     
                print_r( $state );         
                ?><p>Success!</p><?php
            }
            /*EmailFriend( 'pagio91@hotmail.com' );*/
        }
    }
?>
