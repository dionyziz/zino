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
	        
	        /*
	        $libs->Load( 'contacts/contacts' );
	        $username = $username->Get();
	        $pass = $pass->Get();
	        $provider = $provider->Get();
	        ?><p>654687</p><?php
	        $contacts = GetContacts( $username,$pass,$provider);
	        ?><p>alksjgaf</p><?php
	        var_dump( $contacts );
            foreach ( $contacts as $key => $val ) {
                echo '<p>' . $key . ' ' . $val . '</p>';
            }
            */
		
	    $libs->Load( 'grooveshark' );
	    //$res = Groove_GetWidgetId();
	    //echo '<p>' . $res[ "header" ] . " " . $res[ "widgetid" ] . '</p>';
	    //echo '<p>' .  Groove_MakeNewWidget( "16551907", "12120183" ) .  '</p>';

   	   //-----------------spot
  	   $libs->Load( 'poll/poll' );
           $finder = New PollFinder();
           $polls = false;
	   if ( $user->Exists() ) {
                $polls = $finder->FindUserRelated( $user );
                // ONLY FOR BETA
                if ( $polls === false ) {
                    ?><b>Spot connection failed (start daemon!).</b><?php
                }
            }
            if ( $polls === false ) { // anonymous or spot failed
                $libs->Load( 'poll/frontpage' );
                $polls = $finder->FindFrontpageLatest( 0 , 4 );
            }

  	    ?><div class="list">
                <h2 class="pheading">Δημοσκοπήσεις <span class="small1">(<a href="polls">προβολή όλων</a>)</span></h2><?php
                foreach ( $polls as $poll ) {
                    $domain = str_replace( '*', urlencode( $poll->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
                    $url = $domain . 'polls/' . $poll->Url;
	   	    ?><p>
		      <a href="<?php
    	            echo $url;
                    ?>"> <?php 
		    echo $url . "</a> - ";
	 	    echo htmlspecialchars( $poll->Question ) . "</p>";
                }
            ?></div><?php

            //

        }
    }
?>
