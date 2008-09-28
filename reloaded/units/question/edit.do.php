<?php
	function UnitQuestionEdit( tInteger $id , tString $answer, tCoalaPointer $callback ) {
		global $user;
		global $libs;

        $id = $id->Get();
        $answer = $answer->Get();
        
		$libs->Load( 'question' );
        $question = new Question( $id );
        if ( $answer == '' || !$question->Exists() ) {
            return;
        }

		$formatted = mformatanswers( array( myucfirst( $answer ) ) );
		
        echo $callback;
        ?>( <?php
        echo $id;
        ?>, <?php
        echo w_json_encode( $formatted[ 0 ] );
        ?>, <?php
        echo w_json_encode( $answer );
        ?> );<?php
        $user->AnswerQuestion( $id, $answer );
	}

?>
