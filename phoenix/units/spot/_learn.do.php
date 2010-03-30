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
                foreach( $info as $key => $value ) {
                    echo $value . ' - ';
                }
                ?>' );<?php
                
                Element( 'url', $journal );
                ?>window.location.href = '<?php
                echo $url;
                ?>';<?php
                break;
            default:
                ?>alert( 'Wrong item type' );<?php
                return;
        }
    }
?>
