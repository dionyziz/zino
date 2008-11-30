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
	        
	        ?><h3>Στείλε προσκλήσεις στους φίλους σου!( Μονο hotmail )</h3>
    	          <form method="post" action="do/findcontacts">
                  <p>Email : <input type="text" maxlength="30" name="email" /></p>  
                  <p>Κωδικός : <input type="password" maxlength="30" name="pass" /></p>
                  <p><input type="submit" value="Αποστολή" /></p>
                  </form>
                  <p>Ο κωδικός σου δεν θα αποθηκευτεί.</p>
            <?php
        }
    }
?>
