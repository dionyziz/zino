<?php
    function UnitJournalDelete( tInteger $journalid ) {
        global $user;
        global $xc_settings;
        global $libs;
        
        $libs->Load( 'journal/journal' );
        $journal = New Journal( $journalid->Get() );
        
        if ( $journal->Userid == $user->Id || $user->HasPermission( PERMISSION_JOURNAL_DELETE_ALL ) ) {
            $journal->Delete();
            $domain = str_replace( '*', urlencode( $journal->User->Subdomain ), $xc_settings[ 'usersubdomains' ] );
            $url = $domain . 'journals';
            ?>window.location.href = '<?php
            echo $url;
            ?>';<?php
        }
    }
?>
