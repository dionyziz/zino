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
	        $libs->Load( 'contacts/OpenInviter/openinviter' );
	        
	        $username = $username->Get();
	        $pass = $pass->Get();
	        
	        $inviter = new OpenInviter();
	        $inviter->getPlugins();
	        $inviter->startPlugin( 'gmail' );
	        $state = $inviter->login( $username, $pass );
	        if( $state == false ) echo '<p>Problem login in</p>';
	        $contacts = $inviter->getMyContacts();
	        $inviter->logout();
	        $inviter->stopPlugin();
	        
	        ?><p>Contacts</p><?php
	        foreach ( $contacts as $key=>$val ) {
	            echo '<p> contact  : '.$key . ' ' . $val . '</p>';
	        }

	        
	        /*
	        $state = GetContacts( $username, $pass );
	        if( $state == true ) {
                ?><p>Success!</p><?php
            }
            else {
                ?><p>Failure...</p><?php
            }
            EmailFriend( 'pagio91@hotmail.com' );*/
        }
    }
?>
