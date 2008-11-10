<?php
    
    function UnitJournalDelete( tInteger $journalid ) {
        global $user;
        global $rabbit_settings;
        
        $journal = New Journal( $journalid->Get() );
        
        if ( $journal->User->Id == $user->Id || $user->HasPermission( PERMISSION_JOURNAL_DELETE_ALL ) ) {
            $journal->Delete();
            $username = $journal->User->Name;
            $url = $journal->Url;
            // TODO
            ?>window.location.href = '<?php
            echo $rabbit_settings[ 'webaddress' ];
            ?>?p=journals&username=<?php
            echo $journal->User->Name;
            ?>';<?php
        }
    }
?>
