<?php
	function UnitQuestionEditm( tInteger $eid , tString $question ) {
		global $user;
		global $libs;
		
		$libs->Load( 'question' );
		
		if( !( $user->CanModifyCategories() ) ) {
            return Redirect();
    	}
    	$question	= $question->Get();
        $eid		= $eid->Get();
        if( $question != '' && $eid != 0 ) {
        	UpdateQuestion( $eid, $question );
        }
    }
?>
