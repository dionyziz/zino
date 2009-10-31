<?php
/*
    Masked by: Rhapsody
    Reason: new ajax loading tabs for settings testing
*/
    function UnitUserSettingsTab( tString $tab ) {
        global $user;
        global $libs;
        
        $libs->Load( 'user/profile' );
        $libs->Load( 'user/settings' );
        
        ob_start();
        
        $tab = $tab->Get();
        
        switch( $tab ) {
            case 'personal':
                ?><form id="personalinfo" action="" ><?php
                    Element( 'user/settings/personal/view' );
                ?></form><?php
                break;
            case 'characteristics':
                ?><form id="characteristicsinfo" action="" ><?php
                    Element( 'user/settings/characteristics/view' );
                ?></form><?php
                break;
            case 'interests':
                ?><form onsubmit="return false" id="interestsinfo" action="" ><?php
                    Element( 'user/settings/interests' );
                ?></form><?php
                break;
            case 'contact':
                ?><form id="contactinfo" action="" ><?php
                    Element( 'user/settings/contact' );
                ?></form><?php
                break;
            case 'settings':
                ?><form id="settingsinfo" action="" ><?php
                    Element( 'user/settings/settings' );
                ?></form><?php
                break;
            default:
                return;
        }
        //a little hack to handle the huge tab elements
        $html = str_split( ob_get_clean(), 1024 );
        
        ?>
        buffer = [];
        $( '#settingsloader' ).fadeOut();
        <?php
        foreach ( $html as $chunk ) {
            ?>buffer.push( <?php
            echo  w_json_encode( $chunk ); 
            ?> );<?php
        }
        ?>$( 'div.settings div.tabs' ).append( buffer.join( "" ) );
        Settings.LoadProperties( '<?php
            echo $tab;
        ?>' );<?php
    }
?>