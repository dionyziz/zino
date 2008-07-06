<?php
	
	function ElementPollSmall( $poll , $showcommnum = false ) {
		global $user;
		global $rabbit_settings; 
		global $water;
		
		$finder = New PollVoteFinder();
		$showresults = $finder->FindByPollAndUser( $poll, $user );
		$water->Trace( 'Poll showresults:' . $showresults);
		//used to show results, will be true if the user has voted or is anonymous
		?><div class="pollsmall" style="width:450px;">
			<h4><a href="<?php
				?>?p=poll&amp;id=<?php
				echo $poll->Id;
			?>"><?php
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
										
									?><div class="percentagebar" style="width:<?php
									echo 24 + $option->Percentage * 196;
									?>px;">
										<div class="leftrounded"></div>
										<div class="rightrounded"></div>
										<div class="middlerounded"></div>
									</div>
									<span><?php
									echo round( $option->Percentage * 100 , 0 );
									?>%</span>
									</dd><?php
								}
								else {
									?><dt class="voteterm"><input type="radio" name="poll_<?php
									echo $poll->Id;
									?>" value="<?php
									echo $option->Id;
									?>" onclick="PollView.Vote( '<?php
									echo $option->Id;
									?>' , '<?php
									echo $poll->Id;
									?>' , this );" /></dt>
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
							<dd><a href="?p=poll&amp;id=<?php
							echo $poll->Id;
							?>"><?php
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
			<div class="voting" style="width:450px;"><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>ajax-loader.gif" alt="Παρακαλώ περιμένετε..." title="Παρακαλώ περιμένετε..." /> Παρακαλώ περιμένετε...
			</div>
		</div><?php
	}
?>
