<?php
	
	function UnitUserSettingsTagsNew( tString $text , tInteger $typeid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		
		$libs->Load( 'tag' );
		
		if ( $user->Exists() ) {
			$tag = New Tag();
			$tag->Text = $text->Get();
			$tag->Typeid = $typeid->Get();
			$tag->Save();
			?>$( <?php
			echo $node;
			?> ).find( 'a' ).click( function( id , node ) { 
				Settings.RemoveInterest( '<?php
				echo $tag->Id;
				?>' , <?php
				echo $node;
				?> );
			} )
			.fadeIn( 200 );<?php
		}
	}
?>
