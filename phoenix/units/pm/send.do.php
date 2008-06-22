<?php
    function UnitPmSend( tText $usernames , tText $pmtext ) {
    	global $user;
    	global $libs;

    	$libs->Load( 'pm/pm' );
    	$usernames = $usernames->Get();
    	$pmtext = $pmtext->Get();
    	
    	$test = explode( ' ', $usernames );
        $finder = New UserFinder();
        $userreceivers = $finder->FindByNames( $test );

        if ( empty( $userreceivers ) ) {
            ?>alert('Δεν έχεις ορίσει κάποιον έγκυρο παραλήπτη');<?php
        }
        else {
        	$pm = new PM();
        	$pm->Senderid = $user->Id;
        	$pm->Text = htmlspecialchars( $pmtext );
        	foreach ( $userreceivers as $receiver ) {	
        		$pm->AddReceiver( $receiver );
        	}
        	$pm->Save();
        }

        $finder = New PMFolderFinder();
        $outbox = $finder->FindByUserAndType( $user, PMFOLDER_OUTBOX );

    	?>pms.ShowFolderPm( $( '#folder_<?php
    	echo $outbox->Id; 
    	?>' )[0], <?php
        echo $outbox->Id;
        ?> );<?php
    }
    
?>
