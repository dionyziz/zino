<?php
	
	function UnitPollNew( tString $question , tString $options ) {
		global $user;
		global $libs;
		
		$libs->Load( 'poll/poll' );
		
		$question = $question->Get();
		$options = explode( "|" , $options->Get() );
		?>alert( 'Question is : <?php echo w_json_encode( $question ); ?>' );<?php
		foreach( $options as $option ) {
			?>alert( <?php echo w_json_encode( $option ); ?> );<?php
		}	
	}
?>
