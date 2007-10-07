<?php
	function ElementQuestionList() {
		global $page;
		global $user;
		global $libs;
		global $xc_settings;		
		
		if ( !( $user->CanModifyStories() ) ) {
			return Redirect();
		}
        $libs->Load( 'question' );
        $page->AttachScript( 'js/questions.js' );
        $page->AttachScript( 'js/coala.js' );
        $page->SetTitle( 'Ερωτήσεις Προφίλ' );
        
        ?><h3>Ερωτήσεις Προφίλ</h3><?php
        
        $questions = AllQuestions();
        if ( count( $questions ) ) {
            ?><br />
            <a href="javascript:Questions.create()" id="newq">Δημιούργησε μία Ερώτηση Προφίλ</a><br />
            <form id="newqform" action="do/question/new" method="post" style="display: none;" onkeypress="return submitenter( this, event )">
                <input type="hidden" name="action" value="create" />
                <input type="text" name="question" class="bigtext" value="Γράψε εδώ την νέα Ερώτηση!" onfocus="((this.value=='Γράψε εδώ την νέα Ερώτηση!') ? this.value='' : this.value=this.value);" /> 
                <input type="submit" value="Δημιουργία" class="mybutton" onclick="Questions.create(this.form);" />
                <input type="button" value="Ακύρωση" class="mybutton" onclick="Questions.cancelCreate(this.form);" />
            </form>
            <br /><br />
            <ul id="questions" style="list-style-type: none;"><?php
            
            foreach( $questions as $question ) {
                ?><li <?php
                if ( $user->CanModifyCategories() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
                    ?>onmouseout="Questions.hideLinks( <?php
                        echo $question->Id();
                    ?> )" onmouseover="Questions.showLinks( <?php
                        echo $question->Id();
                    ?> )" <?php
                }
                ?>id="question_<?php
                echo $question->Id();
                ?>"><span id="qraw_<?php
                echo $question->Id();
                ?>"><?php
                echo htmlspecialchars( $question->Question() );
                ?></span><?php
                if ( $user->CanModifyCategories()  && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
                    ?>&nbsp;<a id="qeditlink_<?php
                    echo $question->Id();
                    ?>" style="cursor: pointer; display: none;" onclick="Questions.edit( <?php
                    echo $question->Id();
                    ?> )"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/edit.png" width="12" height="12" alt="Επεξεργασία" title="Επεξεργασία" /></a>
                    <a id="qdeletelink_<?php
                    echo $question->Id();
                    ?>" style="cursor: pointer; display: none;" onclick="Questions.deleteq( <?php
                    echo $question->Id();
                    ?> )"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/delete.png" width="12" height="12" alt="Διαγραφή" title="Διαγραφή" /></a><?php
                }
                ?></li><?php
            }
            
            ?></ul><?php
        }
        else {
            ?>Δεν υπάρχουν ερωτήσεις προφίλ.<br /><?php
        }
	}
?>
