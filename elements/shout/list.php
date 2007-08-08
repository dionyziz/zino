<?php

	function ElementShoutList() {
		global $user;
		global $page;
		global $libs;
		global $water;
		global $xc_settings;
        
		$water->Trace( 'element loaded' );
		$libs->Load( 'shoutbox' );
		
		if ( $user->CanModifyStories() ) {
			$page->AttachScript( 'js/modal.js' );
			$page->AttachScript( 'js/shout.js' );
			$page->AttachScript( 'js/coala.js' );
            $page->AttachStyleSheet( 'css/modal.css' );
		}
		
		$latestshouts = LatestShouts( 1 , 7 );
		?><div class="box shoutbox">
			<div class="header">
				<div style="float:right"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraright.jpg" alt="" /></div>
				<div style="float:left"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>soraleft.jpg" alt="" /></div>
				<h3>Μικρά νέα</h3>
			</div>
			<div class="body"><?php
		            if ( $user->CanModifyStories() ) {
		                ?><a href="javascript:Shoutbox.New()"><img class="newshout" src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/page_new.gif" title="Προσθήκη μικρού νέου" alt="+" /></a><?php
		            }
					$i = 1;
					while ( $shout = array_shift( $latestshouts ) ) {
						Element( 'shout/view', $shout );
						++$i;
						if ( $i > 3 ) {
							break;
						}
					}
					?><div id="moreshouts" class="boxexpand"><?php
					if ( count( $latestshouts ) ) {
						$i = 1;
						while ( $shout = array_shift( $latestshouts ) ) {
							Element( 'shout/view', $shout );
							++$i;
							if ( $i > 4 ) {
								break;
							}
						}
						?><div class="boxlink">
							<a href="index.php?p=allshouts&amp;offset=1">Προβολή Όλων</a>
						</div><?php 
					}
					?>
				</div>
				
				<?php
				if ( $user->CanModifyStories() ) {?>
					<div id="newshout" style="display: none;"><br /><?php
					Element( 'media/emoticons/link' );
					?><form method="post" action="do/shout/new">
						<div><textarea name="shout" style="width: 90%; height: 40px;" rows="7" cols="30" id="newshoutarea" onkeypress="return Shoutbox.checkSize(event);"></textarea>
						<input type="submit" class="mybutton" style="margin-top:2px;" value="Δημιουργία" /> <input type="button" class="mybutton" style="margin-top:2px;" onclick="Shoutbox.New();" value="Ακύρωση" /></div>
					</form><br />
				</div>
				 <?php
				}
				?>
				<a id="shoutboxlink" href="" onclick="ShowMore('shoutbox' );return false;" class="arrow" title="Περισσότερα μικρά νέα"></a>
			</div>
		</div><?php
	}
	
?>
