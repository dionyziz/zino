<?php
    function ElementPmShowfolder( $folder ) {
    	global $water;
    	//this element will show all contents, aka messages for one specific folder (inbox and outbox included for now)
    	//folder is an instanciated class in case of a folder or an id with -1 for inbox and -2 for outbox
    	
    	if ( is_int( $folder ) ) {
    		if ( $folder == -1 ) {
    			$water->Trace( 'inbox selected for viewing' );
    			$inbox = new PMInbox();
    			$messages = $inbox->Messages();
    		}
    		else if ( $folder == -2 ) {
                $water->Trace( 'outbox selected for viewing' );
                $outbox = new PMOutbox();
                $messages = $outbox->Messages();
    		}
    	}
    	else {
    		$messages = $folder->Messages();
    	}
    	$water->Trace( 'messages number: ' . count( $messages ) );
		$pmsinfolder = count( $messages );
		?><script type="text/javascript">
		var pmsinfolder = <?php
		echo $pmsinfolder;
		?>;</script><?php
    	if ( $pmsinfolder == 0 ) {
    		?>Δεν υπάρχουν μηνύματα σε αυτόν τον φάκελο<?php
    	}
    	else {
    		foreach ( $messages as $msg ) {
    			Element( 'pm/onepm' , $msg , $folder );
    		}
    	}
    }
?>
