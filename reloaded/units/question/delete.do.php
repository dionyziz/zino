<?php
	function UnitQuestionDelete( tInteger $questionid ) {
		global $user;
		global $libs;
		
        $questionid = $questionid->Get();
        
		$libs->Load( 'question' );
		
		if ( $user->CanModifyCategories() ) {
			$question = New Question( $questionid );
			
			$question->Kill();
			
			?>g( 'question_<?php
			echo $questionid;
			?>' ).style.display = 'none';<?php
		}
	}

?>
