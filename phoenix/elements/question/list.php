<?php
    class ElementQuestionList extends Element {
        public function Render() {
            global $page;
            global $user;
            global $libs;
            
            if ( !$user->HasPermission( PERMISSION_QUESTION_ACCESS ) ) {
                return Redirect();
            }
            $libs->Load( 'question/question' );
            $page->SetTitle( 'Ερωτήσεις Προφίλ' );
            
            ?><h3>Ερωτήσεις Προφίλ</h3><br />
            <a href="" onclick="Questions.Create();" id="newq">Δημιούργησε μία Ερώτηση Προφίλ</a><br />
            <form style="display: none;" onsubmit="return false;">
                <input type="text" value="Γράψε εδώ την νέα ερώτηση!" class="bigtext" onfocus="((this.value=='Γράψε εδώ την νέα Ερώτηση!') ? this.value='' : this.value=this.value);" />
                <input type="submit" value="Δημιουργία" class="mybutton" />
                <input type="reset" value="Ακύρωση" class="mybutton" />
            </form><br /><br />
            <ul id="questions" style="list-style-type: none;"><?php
            $finder = New QuestionFinder();
            $questions = $finder->FindAll();
            foreach( $questions as $question ) {
                ?><li id="<?php
                echo $question->Id;
                ?>"><span><?php
                echo htmlspecialchars( $question->Text );
                ?></span></li><?php
            }
            ?></ul><?php
        }
    }
?>
