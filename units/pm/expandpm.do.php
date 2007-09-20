<?php
    function UnitPmExpandpm( tInteger $pmid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'pm' );
    	$pmid = $pmid->Get();
    	$pm = new PM( $pmid );
    	if ( !$pm->IsRead() ) {	
            ?>alert( 'Mark as read <?php
            echo $pm->Id;
            ?>' );<?php
            
    		$pm->DelId = 1;	
    		$pm->Save();
    	}
    }
?>
