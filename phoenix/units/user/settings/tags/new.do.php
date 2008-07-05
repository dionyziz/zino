<?php
	
	function UnitUserSettingsTagsNew( tText $text , tInteger $typeid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		
		$libs->Load( 'tag' );
		$text = $text->Get();
		
		if ( $user->Exists() ) {
			if ( strlen( $text ) <= 32 ) {
				$tag = New Tag();
				$tag->Text = $text;
				$tag->Typeid = $typeid->Get();
				$tag->Save();
				?>$( <?php
				echo $node;
				?> ).click( function( id , link ) { 
					Settings.RemoveInterest( '<?php
					echo $tag->Id;
					?>' , <?php
					echo $node;
					?> );
					return false;
				} )
				.fadeIn( 200 );<?php
			}
		}
	}
?>
