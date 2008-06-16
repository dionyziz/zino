<?php

    function ElementPmFolderName( PMFolder $folder ) {
        switch ( $folder->Typeid ) {
            case PMFOLDER_INBOX:
                return 'Εισερχόμενα';
            case PMFOLDER_OUTBOX:
                return 'Απεσταλμένα';
            case PMFOLDER_USER:
                return $folder->Name;
            default:
                throw New Exception( 'Unkown folder typeid ' . $folder->Typeid );
        }
    }

?>
