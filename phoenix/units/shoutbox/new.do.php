<?php
	function UnitShoutboxNew( tText $text , tCoalaPointer $node ) {
		global $user;
		global $libs;
		
        $libs->Load( 'wysiwyg' );
		$libs->Load( 'shoutbox' );
		
		$text = $text->Get();
		if ( $user->Exists() ) {
			if ( trim ( $text ) != '' ) {
				$shout = New Shout();
				$shout->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) ); // TODO: WYSIWYG
				$shout->Save();
				?>$( <?php
				echo $node;
				?> )
				.attr( {
					id : <?php
					echo $shout->Id;
					?> } )
				.find( 'div.toolbox a' ).click( function( shoutid ) {
					Frontpage.DeleteShout( '<?php
					echo $shout->Id;
					?>' );
					return false;
				} );<?php
			}
		}
	}
?>
