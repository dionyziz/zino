<?php
	function ElementFaqQuestionView( tString $id ) {
		global $page;
		global $libs;
		global $user;
		global $page;
		global $xc_settings;
        
        $id = $id->Get();
        
		Element( 'faq/header' );
		
		$faqq = New FAQ_Question( $id );
		
		?><div class="faqq"><?php
			if ( $faqq->IsDeleted() || !$faqq->Exists() ) {
				?>Η ερώτηση που προσπαθείς να δεις έχει διαγραφεί!<?php
			}
			else {
				$page->SetTitle( $faqq->Question() );
				
				?><div class="header">
					<h2 style="margin-bottom: 3px;"><?php
						echo $faqq->Question();
					?></h2>
					<div class="details"><?php
						if ( $faqq->Category()->Id() > 0 ) {
							?><a href="?p=faqc&amp;id=<?php
								echo $faqq->CategoryId();
							?>">
								<img src="image.php?id=<?php
									echo $faqq->Category()->IconId();
								?>" class="avatar" style="width:16px; height:16px;" />
							</a>στο
							<a href="?p=faqc&amp;id=<?php
								echo $faqq->CategoryId();
							?>"><?php
								echo htmlspecialchars( $faqq->Category()->Name() );
							?></a><span>, <?php
						}
						else {
							?><span><?php
						}
						
						echo $faqq->Pageviews();
						?> προβολές</span>
						
					</div><?php
				
					if ( FAQ_CanModify( $user ) ) {
						?><div style="width:100%; text-align: center;">
							<small>
								<a href="?p=addfaqq&amp;eid=<?php
									echo $faqq->Id();
								?>" style="vertical-align: top;">
									<img src="<?php
                                    echo $xc_settings[ 'staticimagesurl' ];
                                    ?>icons/icon_wand.gif" alt="Επεξεργασία Ερώτησης" style="width: 16px; height: 16px; vertical-align: bottom;" />Επεξεργασία
								</a>
								&nbsp;<form action="do/faq/question/delete" method="post" style="display: inline;">
									<input type="hidden" name="id" value="<?php
									echo $faqq->Id();
									?>" />
									<a style="cursor: pointer; vertical-align: top;" onclick="javascript:if( confirm( 'Είσαι σίγουρος ότι θέλεις να διαγράψεις αυτήν την ερώτηση?' ) ) { this.parentNode.submit(); } return false;">
										<img src="<?php
                                        echo $xc_settings[ 'staticimagesurl' ];
                                        ?>icons/page_cross.gif" alt="Διαγραφή Ερώτησης" style="width: 16px; height: 16px; vertical-align: bottom;" />Διαγραφή
									</a>
									<br /><br />
								</form>
							</small>
						</div><?php
					}
				?></div>
				<div class="body"><?php
					echo $faqq->AnswerFormatted();
				?></div>
				<div class="footer"><?php
				
					$prevq = $faqq->PrevQuestion();
					$nextq = $faqq->NextQuestion();
					
					if ( $prevq->Exists() ) {
						?><b>Προηγούμενη Ερώτηση:</b> <a href="faq/<?php
						echo $prevq->Keyword();
						?>"><?php
						echo $prevq->Question();
						?></a><br /><?php
					}
					if ( $nextq->Exists() ) {
						?><b>Επόμενη Ερώτηση:</b> <a href="faq/<?php
						echo $nextq->Keyword();
						?>"><?php
						echo $nextq->Question();
						?></a><br /><br /><?php
					}
					
					?><br /><br />Επιστροφή στην <a href="?p=faq">κεντρική σελίδα</a> των Συχνών Ερωτήσεων<?php
					
				?></div><?php
			}
		?></div><?php
		
		Element( 'faq/footer' );
		
		$faqq->AddPageview();
	}

?>