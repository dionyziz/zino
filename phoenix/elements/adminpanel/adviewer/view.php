<?php    
    class ElementAdminpanelAdviewerView extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        global $libs;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $libs->Load( "admanager" );
	        
	        $page->setTitle( 'Ενεργές Διαφημίσεις' );
	        
	        ?><h2>Ενεργές Διαφημίσεις</h2><?php
            
            $adfinder = new AdFinder();
            $ads = $adfinder->FindAllActive();
            
            if ( count ( $ads ) == 0 )  {
                ?><p>Δεν υπάρχει καμία ενεργή διαφήμιση</p><?php
                return;
            }
            
            ?><table class="stats">
                <tr>
                    <th>Χρήστης</th>
                    <th>Τίτλος</th>
                    <th>Κείμενο</th>
                    <th>Link</th>
                    <th>Εμφανίσεις ακόμα</th>
                    <th>Εικόνα</th>
                </tr>
            <?php  
            
            foreach ( $ads as $ad ) {
                    ?><tr><td><?php
                    echo $ad->Userid;
                    ?></td><td><?php                    
                    echo $ad->Title;
                    ?></td><td><?php                    
                    echo $ad->Body;
                    ?></td><td><?php
                    echo $ad->Url
                    ?></td><td><?php
                    echo $ad->pageviewsremaining;
                    ?></td><td><?php
                    echo Element( 'image/url', $ad->Imageid, $ad->Userid )
                    ?></td></tr><?php
                    
            }
            ?></table><?php          
            
            return;
        }
    }
?>
