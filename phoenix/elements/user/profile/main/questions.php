<?php
    class ElementUserProfileMainQuestions extends Element {
        public function Render( User $theuser ) {
            global $user;

            $answerfinder = New AnswerFinder();
            $answers = $answerfinder->FindByUser( $theuser, 0, 7 );
            if ( empty( $answers ) ) {
                if ( $theuser->Id == $user->Id ) {
                    ?>
                    <div class="questions">
                        <h3>Ερωτήσεις</h3>
                        Δεν έχεις απαντήσει σε κάποια ερώτηση.<br />
                        <br />
                        <a href="<?php
                        Element( 'user/url', $theuser->Id , $theuser->Subdomain );
                        ?>questions" class="button">Απάντησε σε μία ερώτηση</a>
                    </div><?php
                }
            }
            else {
                ?>
                <div class="questions">
                    <h3>Ερωτήσεις <span>(
                    <a href="<?php
                    Element( 'user/url', $theuser->Id , $theuser->Subdomain );
                    ?>questions">προβολή όλων</a>)</span>
                    </h3>
                    <ul><?php
                    foreach ( $answers as $answer ) {
                        Element( 'question/answer/view', $answer );
                    }
                    ?></ul><br />
                </div><?php
            }
        }
    }
?>
