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
			?>var link = $( <?php 
			echo $node;
			?> ).find( 'a' )[ 0 ];
			alert( link );
			alert( link.parentNode );
			$( link ).click( function( id , link ) { 
				Settings.RemoveInterest( '<?php
				echo $tag->Id;
				?>' , link );
				return false;
			} )
			.fadeIn( 200 );<?php
		}
	}
?>
