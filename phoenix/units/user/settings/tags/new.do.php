<?php
	
	function UnitUserSettingsTagsNew( tString $text , tInteger $typeid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		
		$libs->Load( 'tag' );
		
		if ( $user->Exists() ) {
			$tag = New Tag();
			$tag->Userid = $user->Id;
			$tag->Text = $text->Get();
			$tag->Typeid = $typeid->Get();
			$tag->Save();
			?>var link = $( <?php 
			echo $node;
			?> ).find( 'a' );
			$( link ).click( function( id , node ) { 
				Settings.RemoveInterest( '<?php
				echo $tag->Id;
				?>' , link );
				alert( link );
				return false;
			} )
			.fadeIn( 200 );<?php
		}
	}
?>
