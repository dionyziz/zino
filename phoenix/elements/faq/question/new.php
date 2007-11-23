<?php
	function ElementFaqQuestionNew( tInteger $eid, tInteger $category, tBoolean $nocategory, tBoolean $noquestion, tBoolean $noanswer, tBoolean $keywordused ) {
		global $page;
		global $libs;
		global $user;
		
        $eid = $eid->Get();
        $selectedcat = $category->Get();
        $nocategory = $nocategory->Get();
        $noquestion = $noquestion->Get();
        $noanswer = $noanswer->Get();
        $keywordused = $keywordused->Get();
        
		$libs->Load( 'faq' );
		$page->AttachStylesheet( 'css/faq.css' );
		
		if ( !FAQ_CanModify( $user ) ) {
			return Element( '404' );
		}
		
		if ( ValidId( $eid ) ) {
			$question = New FAQ_Question( $eid );
			$onedit = true;
		}
		else {
			$question = New FAQ_Question( array() );
			$onedit = false;
		}
		
		?><form method="post" action="do/faq/question/new"><?php
			if ( $onedit ) {
				?><input type="hidden" name="eid" value="<?php
				echo $eid;
				?>" /><?php
			}
			?>Κατηγορία: <select name="category" id="category">
				<option value="">Επέλεξε</option><?php
				
				$categories = FAQ_AllCategories();
					
				foreach ( $categories as $category ) {
					?><option value="<?php
						echo $category->Id();
					?>"<?php
					if ( $category->Id() == $question->CategoryId() || $category->Id() == $selectedcat ) {
						?> selected="selected"<?php
					}
					?>><?php
						echo htmlspecialchars( $category->Name() );
					?></option><?php
				}
					
			?></select>
			<br /><br />Ερώτηση: <input type="text" size="60" name="question" id="question" value="<?php
				echo htmlspecialchars( $question->Question() );
			?>" /><br /><br />
			Απάντηση:<br /><textarea name="answer" id="answer" cols="60" rows="20"><?php
				echo htmlspecialchars( $question->Answer() );
			?></textarea><br /><br />
			Λέξη-Κλειδί: <input type="text" name="keyword" value="<?php
				echo htmlspecialchars( $question->Keyword() );
			?>" /><br /><br />
			
			<input type="submit" value="<?php
				if ( $onedit ) {
					?>Επεξεργασία<?php
				}
				else {
					?>Δημιουργία<?php
				}
			?>" /> <input type="reset" value="Επαναφορά" />
			
			</form><?php
			
			if ( $nocategory ) {
				?><br />&nbsp;&nbsp;&nbsp;<b>Παρακαλώ επέλεξε μια κατηγορία</b><?php
			}
			else if ( $noquestion ) {
				?><br />&nbsp;&nbsp;&nbsp;<b>Παρακαλώ πληκτρολόγησε μια ερώτηση</b><?php
			}
			else if ( $noanswer ) {
				?><br />&nbsp;&nbsp;&nbsp;<b>Παρακαλώ πληκτρολόγησε μια απάντηση</b><?php
			}
			else if ( $keywordused ) {
				?><br />&nbsp;&nbsp;&nbsp;<b>Η λέξη-κλειδί που πληκτρολόγησες χρησιμοποιείται ήδη</b><?php
			}
	}

?>