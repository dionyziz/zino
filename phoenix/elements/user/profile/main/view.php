<?php
	function ElementUserProfileMainView( $theuser, $commentid, $offset ) {
		global $libs;
		global $user;
		global $water;
		$libs->Load( 'poll/poll' );
		$libs->Load( 'comment' );
		$libs->Load( 'notify' );
		$libs->Load( 'relation/relation' );
		
		if ( $theuser->Profile->Numcomments > 0 ) {
			$finder = New CommentFinder();
			if ( $commentid == 0 ) {
				$comments = $finder->FindByPage( $theuser, $offset , true );
				$total_pages = $comments[ 0 ];
				$comments = $comments[ 1 ];
			}
			else {
				$speccomment = New Comment( $commentid );
				$comments = $finder->FindNear( $theuser, $speccomment );
				$total_pages = $comments[ 0 ];
				$offset = $comments[ 1 ];
				$comments = $comments[ 2 ];
				$finder = New NotificationFinder();
				$notification = $finder->FindByComment( $speccomment );
				if ( $notification ) {
					$notification->Delete();
				}
			}
		}
		$finder = New PollFinder();
		$polls = $finder->FindByUser( $theuser , 0 , 1 );
		$finder = New JournalFinder();
		$journals = $finder->FindByUser( $theuser , 0 , 1 );
		$egoalbum = New Album( $theuser->Egoalbumid );
		if ( $egoalbum->Numphotos > 0 ) {
			$finder = New ImageFinder();
			$images = $finder->FindByAlbum( $egoalbum , 0 , 10 );
		}
		if ( $user->Id == $theuser->Id ) {
			$finder = New NotificationFinder();
			$notifs = $finder->FindByUser( $user , 0 , 5 );
		}
		$showspace = $theuser->Id == $user->Id || strlen( $theuser->Space->GetText( 4 ) ) > 0;
		$shownotifications = $theuser->Id == $user->Id && count( $notifs ) > 0;
		$showuploadavatar = $theuser->Id == $user->Id && $egoalbum->Numphotos == 0;
		//show avatar upload only if there are no notifications
		?><div class="main"><?php
			if ( $shownotifications ) {
				?><div class="notifications">
					<h3>Ενημερώσεις</h3>
					<div class="list"><?php
						Element( 'notification/list' , $notifs );
					?></div>
					<div class="expand">
						<a href="" title="Απόκρυψη"></a>
					</div>
				</div><?php
			}
			if ( $showuploadavatar && !$shownotifications ) {
				?><div class="ybubble">	
					<div class="body">
						<h3>Ανέβασε μια φωτογραφία σου</h3>
						<div class="uploaddiv">
							<object data="?p=upload&amp;albumid=<?php
							echo $user->Egoalbumid;
							?>&amp;typeid=2" class="uploadframe" id="uploadframe" type="text/html">
							</object>
						</div>
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
					echo "egoalbum numphotos are " . $egoalbum->Numphotos . "<br />";
					Element( 'user/profile/main/photos' , $images , $egoalbum );
				}
				else {
					?><ul></ul><?php
				}
			?></div><?php
			$finder = New FriendRelationFinder();
			$friends = $finder->FindByUser( $theuser , 0 , 5 ); 
			
			if ( !empty( $friends ) || ( $user->Id == $theuser->Id && $user->Count->Relations == 0 ) ) { 
				?><div class="friends">
					<h3>Οι φίλοι μου</h3><?php
					if ( $user->Id == $theuser->Id && $user->Count->Relations == 0 ) {
						?>Δεν έχεις προσθέσει κανέναν φίλο. Μπορείς να προσθέσεις φίλους από το προφίλ τους.<?php
					}
					else {
						Element( 'user/list' , $friends );
					}
					if ( $theuser->Count->Relations > 5 ) {
						?><a href="?p=friends&amp;subdomain=<?php
						echo $theuser->Subdomain;
						?>" class="button">Περισσότεροι φίλοι&raquo;</a><?php
					}
				?></div>
				<div class="barfade">
					<div class="leftbar"></div>
					<div class="rightbar"></div>
				</div><?php
			}
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
						?><a href="?p=journals&amp;subdomain=<?php
						echo $theuser->Subdomain;
						?>" class="button">Περισσότερες καταχωρήσεις&raquo;</a><?php
					}	
				?></div>
				<div class="barfade">
					<div class="leftbar"></div>
					<div class="rightbar"></div>
				</div><?php
			}
			if ( $showspace ) {
				?><div class="space">
					<h3>Χώρος</h3><?php
					$showtext = $theuser->Space->GetText( 300 );
					if ( strlen( $theuser->Space->GetText( 5 ) ) > 0 ) {
						?><div><?php
						echo $showtext;
						if ( strlen( $theuser->Space->GetText( 301 ) ) > strlen( $showtext ) ) {
							?>...<?php
						}
						?></div><a href="?p=space&amp;subdomain=<?php
						echo $theuser->Subdomain;
						?>" class="button">Προβολή χώρου&raquo;</a><?php
					}
					else {
						?><div class="nospace">
							Δεν έχεις επεξεργαστεί τον χώρο σου ακόμα. Κάνε click στο παρακάτω link για να τον επεξεργαστείς.
							<br /><a href="?p=editspace">Επεξεργασία χώρου</a>
						</div><?php
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
				if ( $offset <= 0 ) {
					$offset = 1;
				}
				if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) ) {
					Element( 'comment/reply', $theuser->Id, TYPE_USERPROFILE );
				}
				if ( $theuser->Profile->Numcomments > 0 ) {
					Element( 'comment/list' , $comments );
					?><div class="pagifycomments"><?php
                        $link = '?p=user&name=' . $theuser->Name . '&offset=';
						Element( 'pagify' , $offset , $link, $total_pages );

					?></div><?php
				}
			?></div>
		</div><?php	
	}
?>
