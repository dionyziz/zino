<?php

	function UnitFrontpageShoutboxNew( tString $text , tCoalaPointer $node ) {
		global $user;
		global $libs;
		
		$libs->Load( 'shoutbox' );
		
		$text = $text->Get();
		if ( $user->Exists() ) {
			if ( $text != '' ) {
				$shout = New Shout();
				$shout->Text = $text;
				$shout->Save();
				?>var node = <?php
				echo $node;
				?>;
				alert( '<?php echo $shout->Id; ?>' );
				$( node ).find( 'div.toolbox a' ).click( function( shoutid ) {
					Frontpage.DeleteShout( '<?php
					echo $shout->Id;
					?>' , node );
					return false;
				} );<?php
			}
		}
	}
?>
