<?php
    class ElementQuestionAnswerView extends Element {
        public function Render( Answer $answer ) {
            ?><li>
                <p class="question"><?php
                echo htmlspecialchars( $answer->Question->Text );
                ?></p>
                <p class="answer"><?php
                echo htmlspecialchars( $answer->Text );
                ?></p>
            </li><?php
        }
    }
?>
