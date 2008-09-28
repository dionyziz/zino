<?php
	function ElementFaqSearchView( tString $q, tInteger $offset ) {
		global $page;
		
        $q = $q->Get();
		$offset = $offset->Get();
        if ( !ValidId( $offset ) ) {
            $offset = 1;
        }
        
		Element( 'faq/header' );
		
		$search = New Search_FAQQuestions();
		$search->SetOffset( ( $offset - 1 ) * 6 );
		$search->SetLimit( 6 );
		$search->SetFilter( 'delid', 0 );
		$search->SetFilter( 'content', $q );
		$search->SetSortMethod( 'date', 'DESC' );
		$search->NeedTotalLength( true );
		$searchq = $search->Get();
		$totalnum = $search->Length();
					
		$page->SetTitle( "Συχνές ερωτήσεις - Αναζήτηση: " . $q );
		
		?><div class="faqq">
			<div class="header">
				<h2>Αναζήτηση: <?php
				echo htmlspecialchars( $q );
				?></h2><br />
				<small><?php
				if ( $totalnum == 0 ) {
					?>Δε βρέθηκε κανένα αποτέλεσμα<?php
				}
				else if ( $totalnum == 1 )  {
					?>Βρέθηκε ένα αποτέλεσμα<?php
				}
				else {
					?>Βρέθηκαν <?php
					echo $totalnum;
					?> αποτελέσματα<?php
				}
				
				?> με τα κριτήρια αναζήτησής σου.</small><br /><br />
			</div>
			<div class="popularq"><?php
			
				if ( $totalnum > 6 ) {
					?><div style="width: 100%; text-align: right;"><?php
						Element( "pagify", $offset, "faqs&amp;q=" . urlencode( $q ), $totalnum, 6 );
					?></div><?php
				}
				
				foreach( $searchq as $question ) {
					Element( "faq/small", $question );
				}
			?></div>
		</div><?php

		Element( 'faq/footer' );
	}
	
?>