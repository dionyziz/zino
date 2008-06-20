<?php
	function ElementUserProfileMainQuestions( User $theuser ) {
        global $user;

        $answerfinder = New AnswerFinder();
        $answers = $answerfinder->FindByUser( $theuser );
        if ( empty( $answers ) ) {
            if ( $theuser->Id == $user->Id ) {
                ?>Δεν έχεις απαντήσει σε κάποια ερώτηση.<br />
                Απαντώντας σε ερωτήσεις μπορείς να δείξεις πράγματα
                για τον εαυτό σου στους άλλους.<br /><br />
                
                <a href="<?php
                Element( 'user/url', $user );
                ?>questions">Απάντησε σε μία ερώτηση</a><?php
            }
        }
        else {
            ?><ul><?php
            $answers = array_splice( $answers, 0, 7 );
            foreach ( $answers as $answer ) {
                Element( 'question/answer/view', $answer );
            }
            ?></ul><?php
        }
	}
?>
