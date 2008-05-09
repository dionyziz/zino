<?php
	
	function ElementPollSmall( $poll , $showcommnum = false ) {
		//global $page;
		
		//$showcommnum is a boolean variable checking whether the number of comments should appear at the bottom
		//$page->AttachStyleSheet( 'css/poll/small.css' );
		
		
		$showresults = false; //used to show results, will be true if the user has voted or is anonymous
		?><div class="pollsmall">
			<h4><a href=""><?php
			echo htmlspecialchars( $poll->Question );
			?></a></h4>
			<div class="results">	
				<ul><?php
					$finder = New PollOptionFinder();
					$options = $finder->FindByPoll( $poll );
					foreach ( $options as $option ) {
						?><li>
							<dl><?php
								if ( $showresults ) {
									?><dt class="resultterm"><?php
										echo htmlspecialchars( $option->Text );
									?></dt>
									<dd><?php //max width will be 220px and minimum 24px
										
									?><div class="percentagebar" style="width:120px;">
										<div class="leftrounded"></div>
										<div class="rightrounded"></div>
										<div class="middlerounded"></div>
									</div>
									<span>30%</span>
									</dd><?php
								}
								else {
									?><dt class="voteterm"><input type="radio" name="poll_<?php
									echo $poll->Id;
									?>" value="<?php
									echo $option->Id;
									?>" /></dt>
									<dd class="votedefinition"><?php
									echo htmlspecialchars( $option->Text );
									?></dd><?php
								}
							?></dl>
						</li><?php
					}
				?></ul><?php
				if ( $showcommnum ) {
					if ( $poll->Numcomments > 0 ) {
						?><dl class="<?php
						if ( $showresults ) {
							?>pollinfo<?php
						}
						else {
							?>pollinfo2<?php
						}
						?>">
							<dd><a href=""><?php
							echo $poll->Numcomments;
							?> σχόλι<?php
							if ( $poll->Numcomments == 1 ) {
								?>ο<?php
							}
							else { 
								?>α<?php
							}
							?></a></dd>
						</dl><?php
					}
				}
			?></div>
		</div><?php
	}
?>
