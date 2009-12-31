<?php    
    class ElementAdminpanelView extends Element {
        public function Render( tText $username, tText $pass, tText $provider ) {
	        global $page;
	        global $user;
	        global $libs;
		    global $xc_settings;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Κεντρική σελίδα διαχειριστών' );
	        
	        ?><h2>Κεντρική σελίδα διαχειριστών</h2><?php
	        
	        ?><ul><?php		        
		        ?><li><a href="?p=banlist" >Αποκλεισμένοι χρήστες</a></li><?php
		        ?><li><a href="?p=adminlog" >Ενέργειες διαχειριστών</a></li><?php
		        ?><li><a href="?p=adviewer" >Ενεργές διαφημίσεις</a></li><?php
                        ?><li><a href="?p=happeningadmin" >Εκδηλώσεις</a></li><?php
		        ?><li><a href="?p=statistics" >Στατιστικά( νέα σχόλια, δημοσκοπήσεις, χρήστες... )</a></li><?php
                        ?><li><a href="?p=memorystats" >Στατιστικά σχετικά με την χρήση μνήμης απο τα διάφορα scripts</a></li><?php
	        ?></ul><?php
	        
	        $libs->Load( 'content' );
            ?><p>Content</p><?php
			
			$stream = New ContentStream();
            $content = $stream->GetContent( $user );
            foreach ( $content as $object ) {
                ?><p><?php
                echo $object[ "item" ]->Id . "----" . $object[ "created" ];
                ?>  <?php
                foreach ( $object[ "comments" ] as $comm ) { 
                    ?><p><?php
                    echo $comm[ 'text' ] . " " . $comm[ 'user_name' ];
                    ?></p><?php
                }
                ?></p><?php                
            }

        }
    }
?>
