<?php
    function UnitSpotLearn( tInteger $type, tInteger $id, tText $info ) {
        global $user;
        global $xc_settings;
        global $libs;
        
        $info = explode( ',', $info->Get() );
        
        switch( $type->Get() ) {
            case TYPE_JOURNAL:
                $libs->Load( 'journal/journal' );
                
                $journal = New Journal( $id->Get() );
                if ( !$journal->Exists() ) {
                    ?>alert( 'Item does not exist' );<?php
                    return;
                }

                Redirect( "http://www.zino.gr" );
                
                break;
            default:
                ?>alert( 'Wrong item type' );<?php
                return;
        }
    }
?>