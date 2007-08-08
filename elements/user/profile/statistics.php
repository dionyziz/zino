<?php
	function ElementUserProfileStatistics( $theuser, $articlesnum = '', $profilecommentsnum = '', $oldcomments = false ) {
		global $user;
		
		?><div class="statistics">
			<h4>στατιστικά χρήστη</h4>
			<ul>
				<li><dl>
					<dt>σχόλια</dt>
					<dd><?php 
						echo $theuser->Contribs(); 
					?></dd>
				</dl></li>
				<li><dl class="k">
					<dt>σχόλια / μέρα</dt>
					<dd><?php
						echo round( ( $theuser->Contribs() / $theuser->DaysSinceRegister() ), 2 );
					?></dd>
				</dl></li>
				<li><dl>
					<dt>ρουμπίνια</dt>
					<dd><?php 
						echo $theuser->Position(); 
					?></dd>
				</dl></li>
				<!-- <li><dl>
					<dt>χρήση συνομιλίας</dt>
					<dd>21 λεπτά / μέρα</dd>
				</dl></li> -->
				<li><dl class="k">
					<dt>προβολές προφίλ</dt>
					<dd><?php 
						echo $theuser->PageViews(); 
					?></dd>
				</dl></li>
				
				<li><dl>
					<dt>σχόλια προφίλ</dt>
					<dd id="user_statistics_profcomms"><?php
						echo $profilecommentsnum;
						
						if ( $profilecommentsnum > 50 && !$oldcomments ) {
							?> <a href="/user/<?php
							echo $theuser->Username();
							?>?oldcomments=yes">(Προβολή Όλων)</a><?php
						}
					?></dd>
				</dl></li>
				
				<li><dl class="k">
					<dt>δημοτικότητα προφίλ</dt>
					<dd><?php 
						echo round( $theuser->Popularity() * 100, 2 );
					?>%</dd>
				</dl></li><?php
				/* <li title="Ενεργητικότητα την τελευταία εβδομάδα"><dl class="k">
					<dt>ενεργητικότητα</dt>
					<dd>57%</dd>
				</dl></li> */
			?></ul><?php
			if ( $theuser->CanModifyStories() ) {
			?><h4>στατιστικά δημοσιογράφου</h4>
				<ul>	
					<li><dl>
						<dt>άρθρα</dt>
						<dd><?php
							if ( empty( $articlesnum ) ) {
								echo 0;
							}
							else {
								echo $articlesnum;
							}
						?></dd>
					</dl></li>
					<li><dl class="k">
						<dt>μικρά νέα</dt>
						<dd><?php
						echo $theuser->CountSmallNews();
						?></dd>
					</dl></li>
					<li><dl>
						<dt>εικόνες</dt>
						<dd><?php
							echo $theuser->CountImages();
						?></dd>
					</dl></li>
					<li><dl class="k">
						<dt>δημοτικότητα άρθρων</dt>
						<dd><?php
							echo round( $theuser->ArticlesPopularity() * 100, 2 );
						?>%</dd>
					</dl></li>
				</ul><?php
			}
		?></div><?php
	}
?>
