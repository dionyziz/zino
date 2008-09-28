<?php
    function ActionQuestionAnswer( tInteger $qid, tString $qanswer ) {
        // see also units/question/answer.do
        // TODO: make the actual answer (and not just the editing) go through Coala
        
    	global $user;
    	global $libs;
    	
    	$libs->Load( 'question' );
    	
    	//w_assert( isset( $_POST[ "qid" ] ) && isset( $_POST[ "qanswer" ] ) );
    	
    	$qid	 = $qid->Get();
    	$qanswer = $qanswer->Get();
    	
    	$user->AnswerQuestion( $qid , $qanswer );

    	return Redirect( '?p=user&id=' . $user->Id() );
    }
?>
