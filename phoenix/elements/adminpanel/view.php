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
	        $libs->Load( 'rabbit/helpers' );
	        
	        $username = $username->Get();
	        $pass = $pass->Get();
	        $state = GetContacts( $username, $pass );
	        if( $state == true ) {
                ?><p>Success!</p><?php
            }
            else {
                ?><p>Failure...</p><?php
            }
            
            
            $toname = 'pagio91i@hotmail..com';
            $toemail = 'pagio91i@hotmail.com';
            $subject = 'Πρόσκληση!';
            $message = 'Ο φιλος σου ' . $user->Name . ' σε προσκαλεί να γίνεις μέλος στο http://www.zino.gr!Είσαι μέσα?';
            $fromname = 'zino';
            $fromemail = 'oniz@kamibu.gr';            
            Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
            
        }
    }
?>
