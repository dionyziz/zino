<?php
	function UnitQuestionAnswerNew( tInteger $questionid , tText $answertext, tCoalaPointer $callback ) {
		global $user;
		global $libs;
		
		$questionid = $questionid->Get();
		$answertext = $answertext->Get();
		$answertext = trim( $answertext );

		$libs->Load( 'question/question' );
		$libs->Load( 'question/answer' );

		$question = New Question( $questionid );

		$questionfinder = New QuestionFinder();
		$newquestion = $questionfinder->FindNewQuestion( $user );
		
		if ( trim( $answertext ) == '' || !$question->Exists() || $newquestion === false ) {
			return;
		}

		$answer = New Answer();
		$answer->Questionid = $questionid;
		$answer->Userid = $user->Id;
		$answer->Text = $answertext;
		$answer->Save();
		
		echo $callback;
		?>( <?php
		echo $answer->Id;
		?> );<?php
	}
?>
