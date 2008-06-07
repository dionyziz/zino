<?php
	
	function UnitUserSettingsTagsNew( tString $text , tInteger $typeid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		
		$libs->Load( 'tag' );
		
		if ( $user->Exists() ) {
			$tag = New Tag();
			$tag->Text = $text->Get();
			$tag->Typeid = $typeid->Get();
			?>alert( 'text is <?php echo $text->Get(); ?>' );
			alert( 'typeid is <?php echo $typeid->Get(); ?>' );<?php
			$tag->Save();
			?>var link = $( <?php 
			echo $node;
			?> ).find( 'a' );
			$( link ).click( function( id , node ) { 
				Settings.RemoveInterest( '<?php
				echo $tag->Id;
				?>' , link );
				alert( link );
			} )
			.fadeIn( 200 );<?php
		}
	}
?>
