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
		switch( $tab ) {
			case 'personal':
                Element( 'user/settings/personal/view' );
				break;
			case 'characteristics':
                Element( 'user/settings/characteristics/view' );
				break;
			case 'interests':
                Element( 'user/settings/interests' );
				break;
			case 'contact':
                Element( 'user/settings/contact' );
				break;
			case 'account':
                Element( 'user/settings/account' );
				break;
            default:
                return;
		}
		$html = w_json_encode( ob_get_clean() );
		
	  ?>$( '#settingsloader' ).fadeOut();
        $( 'div.settings div.tabs' ).appendTo(
            document.createElement( 'form' ).attr( 'id', '<?php
            echo $tab;
            ?>info' )
            .html( <?php
                echo $html;
            ?> )
        );
		Settings.LoadProperties( '<?php
			echo $tab;
		?>' );<?php
	}
?>