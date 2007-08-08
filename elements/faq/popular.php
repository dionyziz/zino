<?php
	function ElementFaqPopular() {
		global $user;
		global $page;
		global $xc_settings;
        
		$page->SetTitle( 'Συχνές ερωτήσεις' );
		
		Element( 'faq/header' );
		
		?><div class="popularq"><?php
		
			if ( $user->CanModifyCategories() ) {
				?><div style="100%; text-align: right; margin-bottom: 3px;">
					<a href="?p=addfaqq" title="Νέα Ερώτηση">
						<img style="width: 16px; height: 16px;" src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/page_new.gif" /> Νέα Ερώτηση
					</a>
				</div><?php
			}
				
			$search = New Search_FAQQuestions();
			$search->SetLimit( 6 );
			$search->SetFilter( 'delid', 0 );
			$search->SetSortMethod( 'popularity', 'DESC' );
			$popularq = $search->Get();
			
			foreach( $popularq as $question ) {
				Element( 'faq/small', $question );
			}
			
		?></div><?php
		
		Element( 'faq/footer' );
	}

?>