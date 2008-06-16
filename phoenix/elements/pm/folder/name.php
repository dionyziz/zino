<?php

    function ElementPmFolderName( PMFolder $folder ) {
        switch ( $folder->Typeid ) {
            case PMFOLDER_INBOX:
                return 'Εισερχόμενα';
            case PMFOLDER_OUTBOX:
                return 'Απεσταλμένα';
            default:
                return $folder->Name;
        }
    }

?>
