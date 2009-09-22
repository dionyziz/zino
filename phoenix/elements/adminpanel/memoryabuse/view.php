<?php    
    class ElementAdminpanelMemoryabuseView extends Element {
        public function Render() {
	        global $page;
	        global $user;
	        global $libs;
	        
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }
	        
	        $page->setTitle( 'Στατιστικά σχετικά με την χρήση μνήμης απο τα διάφορα scripts' );
	        
	        ?><h2>Στατιστικά σχετικά με την χρήση μνήμης απο τα διάφορα scripts</h2><?php
                
                $libs->Load( 'memoryusage' );	   

                $finder = New MemoryUsageFinder();
                $res =  $finder->FindAll( 0, 100 );


	        ?><table class="stats">
                <tr>
                    <th>Σελίδα</th>
                    <th>Μνήμη</th>
                    <th>Ημερομηνία</th>
                    <th>Χρήστης</th>
                </tr>
                <?php  
                    
                foreach ( $res as $mem ) {
                    ?><tr><td><?php
                    echo htmlspecialchars( $mem->Url );
                    ?></td><td><?php                    
                    echo $mem->Size;
                    ?></td><td><?php                    
                    echo $mem->Created;
                    ?></td><td><?php
                    echo $mem->Userid;
                    ?></td><?php                    
                }
                ?></table><?php     
        }
    }
?>
