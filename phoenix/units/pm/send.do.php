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

        foreach ( $userreceivers as $key => $receiver ) {
            if ( $receiver->Id == $user->Id ) {
                unset( $userreceivers[ $key ] );
            }
        }

        if ( empty( $userreceivers ) ) {
            ?>alert('Δεν έχεις ορίσει κάποιον έγκυρο παραλήπτη');<?php
        }
        else {
            $pm = new PM();
            $pm->Senderid = $user->Id;
            $pmtext = nl2br( htmlspecialchars( $pmtext ) );
            $pmtext = WYSIWYG_PostProcess( $pmtext );
            $pm->Text = $pmtext;
            foreach ( $userreceivers as $receiver ) {    
                $pm->AddReceiver( $receiver );
            }
            $pm->Save();
        }

        $finder = New PMFolderFinder();
        $outbox = $finder->FindByUserAndType( $user, PMFOLDER_OUTBOX );

        ?>
        pms.ShowFolderPm( document.getElementById( 'folder_<?php
        echo $outbox->Id;
        ?>' ), <?php
        echo $outbox->Id;
        ?> );<?php
    }
    
?>
