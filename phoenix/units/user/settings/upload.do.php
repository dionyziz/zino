<?php
    
    function UnitUserSettingsUpload( tInteger $imageid ) {
        $image = New Image( $imageid->Get() );
        
        ?>var inner = <?php
        ob_start();
        Element( 'user/settings/personal/photosmall' , $image );
        echo w_json_encode( ob_get_clean() );
        ?>;
        $( $( 'div.modal div.avatarlist ul li' )[ 0 ] ).html( inner ).show();
        alert( 'adding innerhtml' );
        $( $( 'div.settings div.tabs form#personalinfo div.option div.setting div.avatarlist ul li' )[ 0 ] ).html( inner );
        alert( 'added' );<?php
        if ( $image->Album->Numphotos == 1 ) {
            ?>Coala.Warm( 'user/settings/avatar' , { imageid : <?php
            echo $image->Id;
            ?> } );<?php
        }
    }
?>
