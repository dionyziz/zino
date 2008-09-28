<?php
	class ElementQuestionAnswerView extends Element {
		public function Render( Answer $answer, $owner=false ) {
			global $user;
			global $xc_settings;
			?><li<?php
			if ( $owner ) {
				?> id="q_<?php
				echo $answer->Id;
				?>"<?php
			}
			?>>
				<p class="question"><?php
				echo htmlspecialchars( $answer->Question->Text );
				?></p>
				<p class="answer"><?php
				echo htmlspecialchars( $answer->Text );
				?></p><?php
				if ( $owner ) {
				?><a href="" onclick="Questions.Edit( <?php
					   echo $answer->Id;
					   ?> );return false" title="Επεξεργασία Ερώτησης"><img src="<?php
					   echo $xc_settings[ 'staticimagesurl' ];
					   ?>edit.png" alt="Επεξεργασία Ερώτησης" title="Επεξεργασία Ερώτησης" /></a> 
				  <a href="" onclick="Questions.Delete( <?php
					echo $answer->Id;
					?> );return false" title="Διαγραφή Ερώτησης"><img src="<?php
					echo $xc_settings[ 'staticimagesurl' ];
					?>delete.png" alt="Διαγραφή Ερώτησης" title="Διαγραφή Ερώτησης" /></a><?php
				}
			?></li><?php
		}
	}
?>
