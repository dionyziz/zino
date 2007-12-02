<?php
	function ElementUserProfileQuestions( $theuser , $numanswers ) {
		global $user;
		global $libs;
		global $page;
		global $xc_settings;
		global $water;
        
		$page->AttachScript( 'js/profileq.js' );
		$page->AttachScript( 'js/animations.js' );
		
		// questions content
		if ( $theuser->Id() == $user->Id() ) {
            ?><div><?php // It's most likely that this will look in the code as: <div></div>.Plz don't remove it since it used by a JavaScript function
			if ( $numanswers == 0 ) { 
				?>Κατα τακτά χρονικά διαστήματα θα εμφανίζονται στο προφίλ σου κάποιες ερωτήσεις τις οποίες μπορείς να απαντάς 
					και αφορούν εσένα, ένα άλλο πρόσωπο ή οτιδήποτε άλλο μπορείς να φανταστείς.<br />
				Η εμφάνιση των ερωτήσεων εξαρτάται από τον αριθμό των σχολίων σου. Κάθε ορισμένο αριθμό σχολίων θα σου εμφανίζεται
					και μια ερώτηση προς απάντηση.<br /><br /><?php
			}
			else if ( $numanswers == 1 ) { 
				?>Έχεις απαντήσει μία ερώτηση.<br />Κάνοντας περισσότερα σχόλια θα μπορέσεις να απαντήσεις σε περισσότερες 
				ερωτήσεις που θα σου εμφανιστούνε.<br /><br /><?php
			}
			else if ( $numanswers <= 4 ) { 
				?>Έχεις ήδη απαντήσει σε ορισμένες ερωτήσεις.<br /> 
					Οι επόμενες θα σου γίνουν διαθέσιμες κάνοντας περισσότερα σχόλια.<br /><br /><?php
			}
            ?></div><?php
			
			$water->Profile( "Get unanswered question" );
			
			$question = $user->GetUnansweredQuestion();
			if ( $question !== false ) {
				// 1 question per 10 comments
				if ( $user->Contributions() > $numanswers * 10 && $user->Rights() >= $xc_settings[ 'readonly' ] ) { 
					?><br /><br /><br />
					<div id="newquest">
						<b><?php
							echo myucfirst( $question->Question() ); ?>
						</b><br />
						<form id="newquestform" onsubmit="return Profileq.Save(<?php
                        echo $question->Id();                        
                        ?>);">
						<input type="text" class="mybigtext" size="60" id="qanswer" />&nbsp;
						<a href='' onclick="g('newquestform').onsubmit();return false;" alt="Αποθήκευση" title="Αποθήκευση"><img src="http://static.chit-chat.gr/images/icons/accept.png" /></a>&nbsp;
                        <a href='' onclick="Profileq.changeQuestion( <?php
                        echo $question->Id();
                        ?> );return false;" alt='Αλλαγή Ερώτησης' title='Αλλαγή Ερώτησης'>
                        <img src="http://static.chit-chat.gr/images/icons/arrow_refresh.png" /></a>
					</form></div><?php
				}
			}
			
			$water->ProfileEnd();
		}
		
		$water->Profile( "Show answered questions" );
		$water->Trace( "Answered Questions: " . $numanswers );
		
	
        ?><div><?php 
		$questions = $theuser->GetAnsweredQuestions();
		
		Question_FormatMulti( $questions );
	    if ( is_array( $questions ) ) {
            foreach ( $questions as $question ) {
                ?><br />
                <div>
                    <div class="label"><?php
                        echo myucfirst( $question->Question() ); 
                    ?></div>
                    
                    <div id="qedit_<?php 
                    echo $question->Id();
                    ?>"><?php
                                    
                    echo $question->AnswerFormatted();
                    if ( $theuser->Id() == $user->Id() ) { 
                        ?> <a onclick="Profileq.Edit( '<?php 
                        echo $question->Id();
                        ?>' );return false;" href="" title="Επεξεργασία ερώτησης"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/icon_wand.gif" style="width:16px;height:16px" alt="Επεξεργασία Ερώτησης" /></a>
                        &nbsp;<a onclick="Profileq.Delete( '<?php
                        echo $question->Id();
                        ?>' );return false;" href="" title="Διαγραφή ερώτησης"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/delete.png" style="width:12px;height:12px" alt="Διαγραφή Ερώτησης" /></a>
                        <?php
                    } 
                    ?></div>
                    
                    <div id="qraw_<?php
                    echo $question->Id();
                    ?>" style="display:none"><?php
                    echo htmlspecialchars( $question->Answer() );
                    ?></div>
                </div><?php
            }
        }

		$water->ProfileEnd();
		
		if ( $user->CanModifyCategories() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
			?><br /><br /><a href="?p=questions">Διαχείριση Ερωτήσεων</a><?php
		}
        ?></div><?php
	}
?>
