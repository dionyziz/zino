<?php
	function UnitUserSettingsTagsNew( tText $text , tInteger $typeid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		
		$libs->Load( 'tag' );
		$text = trim( $text->Get() );
		
		if ( !$user->Exists() ) {
			?>alert( "Πρέπει να είσαι συνδεδεμένος για να αλλάξεις τα ενδιαφέροντά σου" );
			window.location.reload();<?php
			return;
		}
		if ( $text === '' ) {
			?>alert( "Δεν μπορείς να δημιουργήσεις κενό ενδιαφέρον" );
			window.location.reload();<?php
			return;
		}
		if ( mb_strlen( $text ) > 32 ) {
			?>alert( "Το κείμενο πρέπει να έχει μήκος λιγότερο από 33 χαρακτήρες" );
			window.location.reload();<?php
			return;
		}

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
?>
