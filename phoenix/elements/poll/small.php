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
									<dd class="votedefinition><?php
									echo htmlspecialchars( $option->Text );
									?></dd><?php
								}
							?></dl>
						</li><?php
					}
					/*
					<li>
						<dl>
							<dt style="float:right;">
								Μεταξύ 2 και 5
							</dt>
							<dd>
								<div class="percentagebar" style="width:150px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>64%</span>
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt style="float:right;">
								Από 5 μέχρι 10
							</dt>
							<dd>
								<div class="percentagebar" style="width:34px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>5,3%</span>
							</dd>
						</dl>
					</li>
					<li>
						<dl>
							<dt style="float:right;">
								Από 10 και πάνω
							</dt>
							<dd>
								<div class="percentagebar" style="width:18px;">
									<div class="leftrounded"></div>
									<div class="rightrounded"></div>
									<div class="middlerounded"></div>
								</div>
								<span>0,7%</span>
							</dd>
						</dl>
					</li>	
					*/
				?></ul><?php
				if ( $showcommnum ) {
					if ( $poll->Numcomments > 0 ) {
						?><dl class="pollinfo">
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

