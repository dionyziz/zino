<?php
	function ElementFaqLatest() {
        global $xc_settings;
        
		?><div class="box">
			<div class="header">
				<div style="float:right"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraright.jpg" /></div>
				<div style="float:left"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraleft.jpg" /></div>
				<h3>Νεότερες Ερωτήσεις</h3>
			</div>
			<div class="body">
				<ol style="list-style-type: square; padding: 2px; padding-left: 16px; margin: 0px;"><?php
				
					$search = New Search_FAQQuestions();
					$search->SetLimit( 8 );
					$search->SetFilter( 'delid', 0 );
					$search->SetSortMethod( 'date', 'DESC' );
					$latestq = $search->Get();
					
					foreach( $latestq as $question ) {
						?><li><a href="faq/<?php
						echo $question->Keyword();
						?>"><?php
						echo htmlspecialchars( $question->Question() );
						?></a></li><?php
					}
				
				?></ol>
			</div>
		</div><?php
	}

?>