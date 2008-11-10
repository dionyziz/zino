<?php
    function ActionJournalNew( tInteger $id , tText $title , tText $text ) {
        global $user;
        global $libs;

        header( 'Content-type: text/plain' );

        $id = $id->Get();
        $title = $title->Get();
        $text = $text->Get();
        
        if ( $id > 0 ) {
            $journal = New Journal( $id );
            if ( $journal->User->Id != $user->Id ) {
                die( 'You can\'t edit this journal' );
                return;
            }
        }
        else {
            if ( !$user->Exists() ) {
                die( 'You must login first' );
                return;
            }
            $journal = New Journal();
        }
        $journal->Title = $title;

        $libs->Load( 'wysiwyg' );
        $result = WYSIWYG_PostProcess( $text );

        $journal->Text = $result;
        $journal->Save();

        $username = $journal->User->Name;
        $url = $journal->Url;
        // TODO
        return Redirect( '?p=journal&id=' . $journal->Id );
    }
?>
