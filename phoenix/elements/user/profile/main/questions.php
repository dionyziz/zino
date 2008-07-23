<?php
    class ElementUserProfileMainQuestions extends Element {
        public function Render( User $theuser ) {
            global $user;

            $answerfinder = New AnswerFinder();
            $answers = $answerfinder->FindByUser( $theuser );
            if ( empty( $answers ) ) {
                if ( $theuser->Id == $user->Id ) {
                    ?>
                    <div class="questions">
                        <h3>Ερωτήσεις</h3>
                        Δεν έχεις απαντήσει σε κάποια ερώτηση.<br />
                        <br />
                        <a href="<?php
                        Element( 'user/url', $theuser );
                        ?>questions" class="button">Απάντησε σε μία ερώτηση</a>
                    </div><?php
                }
            }
            else {
                ?>
                <div class="questions">
                    <h3>Ερωτήσεις</h3>
                        <ul><?php
                        $answers = array_splice( $answers, 0, 7 );
                        foreach ( $answers as $answer ) {
                            Element( 'question/answer/view', $answer );
                        }
                        ?></ul><br />
                        <a href="<?php
                        Element( 'user/url', $theuser );
                        ?>questions" class="button">Περισσότερες ερωτήσεις&raquo;</a>
                </div><?php
            }
        }
    }
?>
