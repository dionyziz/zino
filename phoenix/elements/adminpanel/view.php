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
	        $state = GetContacts( $username, $pass );
	        if( $state == true ) {
                ?><p>Success!</p><?php
            }
            else {
                ?><p>Failure...</p><?php
            }
            
            
            $toname = $user->Name;
            $toemail = 'pagio91@hotmail.com';
            $subject = 'Πρόσκληση απο τον ' . $user->Name . ' στην Zino κοινοτητα';
            $message = 'Ο φιλος σου  http://' . $user->Name . '.zino.gr σε προσκαλεί να γίνεις μέλος στο http://www.zino.gr .
Είσαι μέσα?
                                        
Ευχαριστούμε,
Η Ομάδα του Zino';
            $message = ' Γεια σου Γιώργο,

Ο $user->Name σε πρόσθεσε στους φίλους του στο Zino. Γίνε μέλος στο Zino για να δεις τα προφίλ των φίλων σου, να φτιάξεις το δικό σου, και να μοιραστείς τις φωτογραφίες σου και τα νέα σου.

Για να δεις το προφίλ του $user->Name στο Zino, πήγαινε στο:
http://$user->Name.zino.gr/

Ευχαριστούμε,
Η Ομάδα του Zino';
            $fromname = 'Zino';
            $fromemail = 'noreply@zino.gr';            
            Email( $toname, $toemail, $subject, $message, $fromname, $fromemail );
            Email( $toname, 'pagio91i@gmail.com', $subject, $message, $fromname, $fromemail );
            
        }
    }
?>
