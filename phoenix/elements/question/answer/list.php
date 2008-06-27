<?php
	function ElementQuestionAnswerList( tText $username , tText $subdomain , tInteger $pageno ) {
		global $page;
		global $user;
		global $rabbit_settings;
		global $water;
		
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
			$page->SetTitle( $theuser->Name . " Questions" );
		}
		else {
			$page->SetTitle( $theuser->Name . " questions" );
		}

        $answerfinder = New AnswerFinder();
        $answers = $answerfinder->FindByUser( $theuser );

        ?><div id="answers"><?php
		Element( 'user/sections', 'question' , $theuser );

        ?><div class="questions"><ul><?php
        foreach ( $answers as $answer ) {
            Element( 'question/answer/view', $answer );
        }
        ?></ul></div>
        
        </div><?php
    }
?>
