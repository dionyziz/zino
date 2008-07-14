<?php
    class ElementQuestionAnswerView extends Element {
        public function Render( Answer $answer ) {
            ?><li id="q_<?php
            echo $answer->Id;
            ?>">
                <p class="question"><?php
                echo htmlspecialchars( $answer->Question->Text );
                ?></p>
                <p class="answer"><?php
                echo htmlspecialchars( $answer->Text );
                ?></p>
            	<a href="" onclick="Questions.Delete( <?php
            	echo $answer->Id;
            	?> );return false;" title="Διαγραφή Ερώτησης" />
            </li><?php
        }
    }
?>
