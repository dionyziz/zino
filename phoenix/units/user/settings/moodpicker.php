<?php
    function UnitUserSettingsMoodpicker( tCoalaPointer $func ) {
        echo $func;
        ?>(<?php
        ob_start();
        Element( 'user/settings/personal/mood' );
        echo w_json_encode( ob_get_clean() );
        ?>);<?php
    }
?>
