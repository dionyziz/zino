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
				<div class="list"><?php
					Element( 'notification/list' );
				?></div>
				<div class="expand">
					<a href="" onclick="return false;"></a>
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
