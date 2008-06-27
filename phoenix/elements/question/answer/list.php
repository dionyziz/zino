<?php
	function ElementQuestionAnswerList( tText $username , tText $subdomain , tInteger $pageno ) {
		global $page;
		global $user;
		global $rabbit_settings;
        global $xc_settings;
		
		$username = $username->Get();
		$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $username != '' ) {
			if ( strtolower( $username ) == strtolower( $user->Name ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindByName( $username );
			}
		}
		else if ( $subdomain != '' ) {
			if ( strtolower( $subdomain ) == strtolower( $user->Subdomain ) ) {
				$theuser = $user;
			}
			else {
				$theuser = $finder->FindBySubdomain( $subdomain );
			}
		}	
		if ( !isset( $theuser ) || $theuser === false ) {
			return Element( '404' );
		}
		
		if ( strtoupper( substr( $theuser->Name, 0, 1 ) ) == substr( $theuser->Name, 0, 1 ) ) {
			$page->SetTitle( $theuser->Name . " Ερωτήσεις" );
		}
		else {
			$page->SetTitle( $theuser->Name . " ερωτήσεις" );
		}

        $answerfinder = New AnswerFinder();
        $answers = $answerfinder->FindByUser( $theuser );

        ?><div id="answers"><?php
		Element( 'user/sections', 'question' , $theuser );

        ?><div class="questions"><?php

        if ( $theuser->Id == $user->Id ) {
            $finder = New QuestionFinder();
            $question = $finder->FindNewQuestion( $theuser );
            if ( $question !== false ) {
                ?><div class="newquestion">
                <p class="question"><?php
                echo htmlspecialchars( $question->Text );
                ?></p>
                <p class="answer"><form id="newanswer">
                    <input type="hidden" value="<?php
                    echo $question->Id;
                    ?>" />
                    <input type="text" /> <a href="" onclick="Questions.Answer();return false;"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>accept.png" /></a>
                </form></p>
                </div><?php
            }
        }

        ?><ul><?php
        foreach ( $answers as $answer ) {
            Element( 'question/answer/view', $answer );
        }
        ?></ul></div>
        
        </div><?php
    }
?>
