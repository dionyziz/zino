<?php
    function UnitSpotLearn( tInteger $type, tInteger $id, tIntegerArray $info ) {
        global $user;
        global $xc_settings;
        global $libs;
        
        switch( $type->Get() ) {
            case TYPE_JOURNAL:
                $libs->Load( 'journal/journal' );
                
                $journal = New Journal( $id->Get() );
                if ( !$journal->Exists() ) {
                    ?>alert( 'Item does not exist' );<?php
                    return;
                }
                ?>alert( '<?php
                foreach( $info as $value ) {
                    echo $value . ' - ';
                }
                ?>' );<?php
                
                ?>window.location.href = '<?php
                Element( 'url', $journal );
                ?>';<?php
                break;
            default:
                ?>alert( 'Wrong item type' );<?php
                return;
        }
    }
?>
