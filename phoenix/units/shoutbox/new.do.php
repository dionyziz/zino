<?php
	function UnitShoutboxNew( tText $text , tCoalaPointer $node ) {
		global $user;
		global $libs;
		
        $libs->Load( 'wysiwyg' );
		$libs->Load( 'shoutbox' );
		
		$text = $text->Get();
		if ( !$user->Exists() ) {
			?>alert( "Πρέπει να είσαι συνδεδεμένος για να συμμετέχεις στην συζήτηση" );
			window.location.reload();<?php
			return;
		}
		
		
		if ( trim ( $text ) == '' ) {
			?>alert( "Δεν μπορείς να δημοσιεύσεις κενό μήνυμα" );
			window.location.reload();<?php
			return;
		}
		
		$shout = New Shout();
		$shout->Text = WYSIWYG_PostProcess( htmlspecialchars( $text ) ); // TODO: WYSIWYG
		$shout->Save();
		?>$( <?php
		echo $node;
		?> )
		.attr( {
			id : "s_<?php
			echo $shout->Id;
			?>" } )
		.find( 'div.toolbox a' ).click( function( shoutid ) {
			Frontpage.DeleteShout( '<?php
			echo $shout->Id;
			?>' );
			return false;
		} ).end()
		.find( 'div.text' ).html( "<?php
			echo $shout->Text;
			?>" );<?php	}
?>
