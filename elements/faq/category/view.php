<?php
	function ElementFaqCategoryView( tInteger $id, tInteger $offset ) {
		global $user;
		global $water;
		global $page;
        global $libs;
		global $xc_settings;
        
        $id = $id->Get();
        $libs->Load( 'faq' );
        
		if ( !ValidId( $id ) ) {
			return Element( '404' );
		}
		
		$faqc = New FAQ_Category( $id );
		
		if ( !$faqc->Exists() ) {
			return Element( '404' );
		}
		else if ( $faqc->IsDeleted() ) {
			?>Η κατηγορία αυτή έχει διαγραφεί!<?php
			return;
		}
		
		$page->SetTitle( "Συχνές ερωτήσεις: " . $faqc->Name() );
		
        Element( 'faq/header' );
				
		?><div class="faqq">
			<div class="header"><?php
				
				Element( "image", $faqc->Icon(), 50, 50, "avatar", "float:left;", $faqc->Name(), $faqc->Name() );
				
				?><h2>Κατηγορία: <?php
				echo htmlspecialchars( $faqc->Name() );
				?></h2><br />
				<small><?php
				echo htmlspecialchars( $faqc->Description() );
				?></small><br />				
			</div><br /><?php
			
			if ( FAQ_CanModify( $user ) ) {
				?><div style="width:100%; text-align: center;">
					<small>
						<a style="vertical-align:top;" href="?p=addfaqc&amp;eid=<?php
							echo $faqc->Id();
						?>">
							<img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/icon_wand.gif" alt="Επεξεργασία Κατηγορίας" style="width: 16px; height: 16px; vertical-align: bottom;" />Επεξεργασία
						</a>
						&nbsp;<form action="do/faq/category/delete" method="post" style="display: inline;">
							<input type="hidden" name="id" value="<?php
							echo $faqc->Id();
							?>" />
							<a style="cursor: pointer; vertical-align: top;" onclick="javascript:if( confirm( 'Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτήν την κατηγορία?' ) ) { this.parentNode.submit(); } return false;">
								<img src="<?php
                                echo $xc_settings[ 'staticimagesurl' ];
                                ?>icons/page_cross.gif" alt="Διαγραφή Κατηγορίας" style="width: 16px; height: 16px; vertical-align: bottom;" />Διαγραφή
							</a>
						</form>
						&nbsp;<a style="vertical-align:top;" href="?p=addfaqq&amp;category=<?php
							echo $faqc->Id();
						?>">
							<img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/page_new.gif" alt="Επεξεργασία Κατηγορίας" style="width: 16px; height: 16px; vertical-align: bottom;" />Νέα ερώτηση
						</a>
						<br />
					</small>
				</div><?php
			}
			
			?><div class="popularq" style="margin-top: 7px;"><?php
					
				$offset = $offset->Get();
				
				if ( $offset < 1 ) {
					$offset = 1;
				}
				
				$search = New Search_FAQQuestions();
				$search->SetOffset( ($offset-1) * 8 );
				$search->SetLimit( 8 );
				$search->SetFilter( 'delid', 0 );
				$search->SetFilter( 'category', $faqc->Id() );
				$search->SetSortMethod( 'date', 'ASC' );
				$search->NeedTotalLength( true );
				$categoryq = $search->Get();
				$questionsnum = $search->Length();
				
				$water->Trace( "FAQ category questions num: " . $questionsnum );
				
				foreach( $categoryq as $question ) {
					Element( "faq/small", $question );
				}
				
				if ( count( $categoryq ) == 0 ) {
					?>Δεν υπάρχουν ερωτήσεις σε αυτήν την κατηγορία.<br /><?php
					if ( FAQ_CanModify( $user ) ) {
						?><a href="?p=addfaqc&amp;category=<?php
						echo $faqc->Id();
						?>">Πρόσθεσε μία τώρα!</a><?php
					}
				}				
				
				if ( $questionsnum > 8 ) {
					Element( "pagify", $offset, "faqc&amp;id=" . $faqc->Id(), $questionsnum, 8 );
				}
				
			?></div>
		</div><?php
		
		Element( 'faq/footer' );
	}
	
?>