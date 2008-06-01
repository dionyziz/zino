<?php
	function ElementUserProfileMainView( $theuser ) {
		global $libs;
		global $user;
		$libs->Load( 'poll/poll' );
		
		$finder = New PollFinder();
		$polls = $finder->FindByUser( $theuser , 0 , 1 );
		$finder = New JournalFinder();
		$journals = $finder->FindByUser( $theuser , 0 , 1 );
		$egoalbum = New Album( $theuser->Egoalbumid );
		if ( $egoalbum->Numphotos > 0 ) {
			$finder = New ImageFinder();
			$images = $finder->FindByAlbum( $egoalbum , 0 , 10 );
		}
		?><div class="main">
			<div class="notifications">
				<h3>Ενημερώσεις</h3>
				<div class="list">
					<div class="event">
						<div class="toolbox">
							<span class="time">πριν ένα μήνα και 2 μέρες</span>
						</div>
						<div class="who">
							<a href="http://dionyziz.beta.zino.gr/phoenix/"><img src="http://images.zino.gr/media/1/_2/2_100.jpg" class="avatar" style="width:50px;height:50px;" title="dionyziz" alt="dionyziz" />dionyziz</a> έγραψε:
						</div>
						<div class="subject">
							<p><span class="text">"ante trava kai pidi3ou"</span>
								, στην εικόνα <a href="https://beta.zino.gr/phoenix/?p=photo&amp;id=16&amp;commentid=2" class="itempic"><img src="http://images.zino.gr/media/2/_16/16_100.jpg" style="width:100px;height:100px;" /></a>				
							</p>
							<a href="https://beta.zino.gr/phoenix/?p=photo&amp;id=16&amp;commentid=2" class="viewcom">Προβολή σχολίου&raquo;</a>
						</div>
					</div>
					<div class="event">
						<div class="toolbox">
							<span class="time">πριν λίγο</span>
						</div>
						<div class="who">
							<a href="http://teddy.zino.gr">
								<img src="images/avatars/teddy.jpg" class="avatar" alt="morvena" />
								Teddy
							</a> έγραψε:
						</div>
						<div class="subject">
							<p>
								<span class="text">"etsi american muscle, respe..."</span>
								, στη φωτογραφία
								<a href="#" class="itempic"><img src="images/dodge.jpg" alt="dodge" /></a>
							</p>
							<a href="#" class="viewcom">Προβολή σχολίου&raquo;</a>
						</div>
					</div>
					<div class="event">
						<div class="toolbox">
							<span class="time">πριν λίγο</span>
						</div>
						<div class="who">
							<a href="http://morvena.zino.gr">
								<img src="images/avatars/morvena.jpg" class="avatar" alt="morvena" />
								Morvena
							</a> έγραψε:
						</div>
						<div class="subject">
							<p>
								<span class="text">"eleos mori skatoulitsa"</span>
								, στο ημερολόγιο
								<a href="#">Βάζω τα φτερά μου και το παίζω πεταλούδος</a>
							</p>
							<a href="#" class="viewcom">Προβολή σχολίου&raquo;</a>
						</div>
					</div>
				</div>
			</div><?php
			if ( $theuser->Id == $user->Id && $egoalbum->Numphotos == 0 ) {
				?><div class="ybubble">	
					<h3>Ανέβασε μια φωτογραφία σου</h3>
					<div class="uploaddiv">
						<object data="?p=upload&amp;albumid=<?php
						echo $user->Egoalbumid;
						?>&amp;typeid=2" class="uploadframe" id="uploadframe" type="text/html">
						</object>
					</div>
					<i class="bl"></i>
					<i class="br"></i>
				</div><?php
			}
			?><div class="photos"<?php
			if ( $egoalbum->Numphotos == 0 ) {
				?> style="display:none"<?php
			}
			?>><?php
				if ( $egoalbum->Numphotos > 0 ) {
					Element( 'user/profile/main/photos' , $theuser , $images , $egoalbum );
				}
				else {
					?><ul></ul><?php
				}
			?></div>
			<div class="friends">
				<h3>Οι φίλοι μου</h3><?php
				Element( 'user/list' );
				?><a href="" class="button">Περισσότεροι φίλοι&raquo;</a>
			</div><?php
			//check if friends empty or not
			?><div class="barfade">
				<div class="leftbar"></div>
				<div class="rightbar"></div>
			</div><?php
			if ( !empty( $polls ) || ( $user->Id == $theuser->Id && $user->Count->Polls == 0 ) ) {
				?><div class="lastpoll">
					<h3>Δημοσκοπήσεις</h3><?php
					if ( $user->Id == $theuser->Id && $user->Count->Polls == 0 ) {
						?><div class="nopolls">
						Δεν έχεις καμία δημοσκόπηση. Κάνε click στο παρακάτω link για να μεταβείς στη σελίδα
						με τις δημοσκοπήσεις και να δημιουργήσεις μια.
						<div><a href="?p=polls&amp;username=<?php
						echo $user->Subdomain;
						?>">Δημοσκοπήσεις</a>
						</div>
						</div><?php
					} 
					else {
						?><div class="container"><?php
						Element( 'poll/small' , $polls[ 0 ] , true );
						?></div>
						<a href="?p=polls&amp;username=<?php
						echo $theuser->Subdomain;
						?>" class="button">Περισσότερες δημοσκοπήσεις&raquo;</a><?php
					}
				?></div><?php
			}
			?><div class="questions">
				<h3>Ερωτήσεις</h3><?php
				Element( 'user/profile/main/questions' , $theuser );
				?><a href="" class="button">Περισσότερες ερωτήσεις&raquo;</a>
			</div><?php
			if ( !empty( $polls ) /*or not empty questions*/ ) {
				?><div class="barfade" style="margin-top:20px;clear:right;">
					<div class="leftbar"></div>
					<div class="rightbar"></div>
				</div><?php
			}
			?><div style="clear:right"></div><?php
			if ( !empty( $journals ) || ( $user->Id == $theuser->Id && $user->Count->Journals == 0 ) ) {
				?><div class="lastjournal">
					<h3>Ημερολόγιο</h3><?php
					if ( $user->Id == $theuser->Id && $user->Count->Journals == 0 ) {
						?><div class="nojournals">
						Δεν έχεις καμία καταχώρηση.<br />
						Κανε click στο παρακάτω link για να δημιουργήσεις μια.
						<div><a href="?p=addjournal">Δημιουργία καταχώρησης</a></div>
						</div><?php
					}
					else {
						Element( 'journal/small' , $journals[ 0 ] );
						?><a href="?p=journals&amp;username=<?php
						echo $theuser->Subdomain;
						?>" class="button">Περισσότερες καταχωρήσεις&raquo;</a><?php
					}	
				?></div>
				<div class="barfade">
					<div class="leftbar"></div>
					<div class="rightbar"></div>
				</div><?php
			}
			?><div class="comments">
				<h3>Σχόλια στο προφίλ <?php
				if ( $theuser->Gender == 'm' || $user->Gender == '-' ) {
					?>του <?php
				}
				else {
					?>της <?php
				}
				Element( 'user/name' , $theuser , false );
				?></h3><?php
				//Element( 'comment/list' );
			?></div>
		</div><?php	
	}
?>
