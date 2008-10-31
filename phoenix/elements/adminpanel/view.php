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
	        //$state = GetContacts( $username, $pass );
	        $state = true;
	        if( $state == true ) {
                ?><p>Success!</p><?php
            }
            else {
                ?><p>Failure...</p><?php
            }
            
            
            $toname = 'pagio91';// 'φιλος του ' . $user->Name;
            $toemail = 'pagio91@hotmail.com';
            $subject = 'Πρόσκληση απο τον ' . $user->Name;
            $message = 'Ο φιλος σου ' . $user->Name . ' σε προσκαλεί να γίνεις μέλος στο http://www.zino.gr! Είσαι μέσα?';
            $fromname = 'Zino community - ' . $user->Name;
            $fromemail = 'oniz@kamibu.gr';            
            Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
            
        }
    }
?>
