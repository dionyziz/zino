<?php

	function ElementFaqSmall( $faqq ) {
		?><div>
			<h2 style="margin-bottom: 1px;"><a href="faq/<?php
				echo $faqq->Keyword();
			?>"><?php
				echo htmlspecialchars( $faqq->Question() );
			?></a></h2>
			<div class="details"><?php
				if ( $faqq->Category()->Id() > 0 ) {
					?><a href="?p=faqc&amp;id=<?php
						echo $faqq->CategoryId();
					?>"><?php
					
					Element( "image", $faqq->Category()->Icon(), 16, 16, "", "margin-top: 1px; padding: 2px;", $faqq->Category()->Name(), $faqq->Category()->Name() );
						
					?></a>στο
					<a href="?p=faqc&amp;id=<?php
						echo $faqq->CategoryId();
					?>"><?php
						echo htmlspecialchars( $faqq->Category()->Name() );
					?></a><span>, <?php
				}
				else {
					?><span><?php
				}
				
				echo $faqq->Pageviews() . " προβολές</span>";
			?></div>
			<div class="answer"><?php
				echo $faqq->AnswerFormatted();
				// echo substr( htmlspecialchars( $faqq->Answer() ), 0, 600 );
			?></div>
		</div><?php
	}
	
?>