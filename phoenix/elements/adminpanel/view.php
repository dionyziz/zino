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
	        $libs->Load( 'contacts/fetcher' );
	        $libs->Load( 'contacts/contacts' );
	        
	        $username = $username->Get();
	        $pass = $pass->Get();
	        $fetcher = new ContactsFetcher();
	        $state = $fetcher->Login( $username, $pass );
	        if ( $state == true ) {    	        
    	        $contacts = $fetcher->Retrieve();	        
    	        $contact = new Contact();
    	        foreach ( $contacts as $key=>$val ) {
                    echo '<p>'.$key.' '.$val.'</p>';
                    $contact->AddContact( $key, $username );
                }
            }
            else {
                ?><p>There was a problem while trying to retreive your contacts</p><?php
            }
            /*
            $to = 'pagio91i@gmail.com';
            $subject = 'Zino';
            $message = 'Ο φιλος σου Χ σε προσκαλεί να γίνεις μέλος στο http://www.zino.gr!Είσαι μέσα?';
            $headers = 'From: oniz@kamibu.gr';
            mail( $to, $subject, $message, $headers );*/
        }
    }
?>
