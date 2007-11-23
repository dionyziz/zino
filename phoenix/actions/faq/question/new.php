<?php
    function ActionFAQQuestionNew( tInteger $eid, tInteger $category, tString $question, tString $answer, tString $keyword ) {
    	global $user;
    	global $libs;
    	
        $eid = $eid->Get();
        $category = $category->Get();
        $question = $question->Get();
        $answer = $answer->Get();
        $keyword = $keyword->Get();
        
    	$libs->Load( 'faq' );
    	
    	if ( !FAQ_CanModify( $user ) ) {
            return Redirect();
    	}

		$urleid = ($eid != 0) ? '&eid=' . $eid : "";
    	
    	if ( empty( $category ) ) {
            return Redirect( "?p=addfaqq$urleid&nocategory=yes" );
    	}
    	if ( empty( $question ) ) {
            return Redirect( "?p=addfaqq$urleid&noquestion=yes" );
    	}
    	if ( empty( $answer ) ) {
            return Redirect( "index.php?p=addfaqq$urleid&noanswer=yes" );
    	}
    	
    	if ( $eid != 0 ) {
    		$newquestion = New FAQ_Question( $eid );
    		$update = $newquestion->Update( $question, $answer, $keyword, $category );
    		if ( $update > 0 ) {
    			$action = $newquestion->Id();
    		}
    		else if ( $update == -3 ) {
    			return Redirect( '?p=addfaqq&eid=' . $eid . '&keywordused=yes' );
    		}
    	}
    	else {
    		$action = FAQ_MakeQuestion( $question, $answer, $keyword, $category );
    	}
    	
    	if ( $action > 0 ) {
            return Redirect( '?p=faqq&id=' . $action );
    	}
    	else if ( $action == -2 ) {
    		return Redirect( '?p=addfaqq&keywordused=yes' );
    	}
        return Redirect( '?p=faqq&error=yes' );
    }
?>
