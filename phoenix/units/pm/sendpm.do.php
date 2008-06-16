<?php
    function UnitPmSendpm( tString $usernames, tString $pmtext ) {
    	global $user;
    	global $libs;

    	$libs->Load( 'pm/pm' );

    	$usernames = $usernames->Get();
    	$pmtext = $pmtext->Get();
    	
    	$split = preg_split( '#[ ,]+#', $usernames );
    
        $finder = New UserFinder();
    	$userreceivers = $finder->FindByNames( $split );

        if ( empty( $userreceivers ) ) {
            ?>alert('Δεν έχεις ορίσει κάποιον έγκυρο παραλήπτη');<?php
        }
        else {
        	$pm = new PM();
        	$pm->Senderid = $user->Id;
        	$pm->Text = $pmtext;
        	foreach ( $userreceivers as $receiver ) {	
        		$pm->AddReceiver( $receiver );
        	}
        	$pm->Save();
        }

        $finder = New PMFolderFinder();
        $outbox = $finder->FindByUserAndType( $user, PMFOLDER_OUTBOX );
        
    	?>pms.ShowFolderPm( document.getElementById( 'sentfolder' ), <?php
        echo $outbox->Id;
        ?> );<?php
    }
    
?>
