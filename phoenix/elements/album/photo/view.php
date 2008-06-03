<?php
	
	function ElementAlbumPhotoView( tInteger $id , tInteger $commentid , tInteger $offset ) {
		global $user;
		global $page;
		global $libs;
		global $water;
		global $xc_settings;
		
		$libs->Load( 'comment' );
		$libs->Load( 'favourite' );
		$id = $id->Get();
		$commentid = $commentid->Get();
		$offset = $offset->Get();
		$image = New Image( $id );
		
		if( !$image->Exists() ) {
			?>Η φωτογραφία δεν υπάρχει<div class="eof"></div><?php
			return;
		}		Element( 'user/sections', 'album' , $image->User );
		if ( $image->IsDeleted() ) {
			?>Η φωτογραφία έχει διαγραφεί<div class="eof"></div><?php
			return;
		}

		if ( $image->Name != "" ) {
			$title = htmlspecialchars( $image->Name );
			$page->SetTitle( $title );
		}
		else {
			if ( $image->Album->User->Egoalbumid == $image->Album->Id ) {
				if ( strtoupper( substr( $image->Album->User->Name, 0, 1 ) ) == substr( $image->Album->User->Name, 0, 1 ) ) {
					$page->SetTitle( $image->Album->User->Name . " Φωτογραφίες" );
				}
				else {
					$page->SetTitle( $image->Album->User->Name . " φωτογραφίες" );
				}
				$title = $image->User->Name;
			}	
			else {
				$page->SetTitle( $image->Album->Name );
				$title = htmlspecialchars( $image->Album->Name );
			}
		}
		if ( $offset <= 0 ) {
			$offset = 1;
		}
		$finder = New FavouriteFinder();
		$fav = $finder->FindByUserAndEntity( $user, $image );
		?><div id="photoview">
			<h2><?php
			echo htmlspecialchars( $image->Name );
			?></h2>
			<span>στο album</span> <a href="?p=album&amp;id=<?php
			echo $image->Album->Id;
			?>"><?php
			if ( $image->Album->Id == $image->User->Egoalbumid ) {
				?>Φωτογραφίες μου<?php
			}
			else {
				echo htmlspecialchars( $image->Album->Name );
			}
			?></a>
			<dl><?php
				if ( $image->Numcomments > 0 ) {
					?><dd class="commentsnum"><?php
					echo $image->Numcomments;
					?> σχόλι<?php
					if ( $image->Numcomments == 1 ) {
						?>ο<?php
					}
					else {
						?>α<?php
					}
					?></dd><?php
				}
				if( $user->Id != $image->User->Id ) { 
					?><dd class="addfav"><a href="" class="<?php
					if ( !$fav ) {
						?>add<?php
					}
					else {
						?>isadded<?php
					}
					?>" title="<?php
					if ( !$fav ) {
						?>Προσθήκη στα αγαπημένα<?php
					} 
					else {
						?>Αγαπημένο<?php
					}
					?>" onclick="PhotoView.AddFav( '<?php
					echo $image->Id;
					?>' , this );return false;"><?php
					if ( !$fav ) {
						?>Προσθήκη στα αγαπημένα<?php
					}
					?></a></dd><?php
				}
			?></dl><?php
			if ( $image->User->Id == $user->Id || $user->HasPermission( PERMISSION_IMAGE_DELETE_ALL ) ) {
				?><div class="owner">
					<div class="edit"><a href="" onclick="PhotoView.Rename( '<?php
					echo $image->Id;
					?>' , <?php
					echo htmlspecialchars( w_json_encode( $image->Album->Name ) );
					?> );return false;"><?php
					if ( $image->Name == '' ) {
						?>Όρισε όνομα<?php
					}
					else {
						?>Μετονομασία<?php
					}
					?></a></div>
					<div class="delete"><a href="" onclick="PhotoView.Delete( '<?php
					echo $image->Id;
					?>' );return false;">Διαγραφή</a></div><?php
					if ( $image->Album->Mainimage != $image->Id ) {
						?><div class="mainimage"><a href="" onclick="PhotoView.MainImage( '<?php
						echo $image->Id;
						?>' );return false;">
						Ορισμός προεπιλεγμένης</a>
						</div><?php
					}
				?></div><?php
			}
			?><div class="eof"></div>
			<div class="thephoto"><?php
				Element( 'image' , $image , IMAGE_FULLVIEW, '' , $title , $title , '' , false , 0 , 0 );
			?></div><?php
			if ( $image->Album->Numphotos > 1 ) {
				?><div class="photothumbs"><?php
					$finder = New ImageFinder();
					$photos = $finder->FindAround( $image , 12 );
					$water->Trace( 'numphotos is: ' . count( $photos ) );
					$pivot = $i = 0;
					foreach ( $photos as $photo ) {
						if ( $photo->Id == $image->Id ) {
							$pivot = $i;
							break;
						}
						++$i;
					}
			        if ( $pivot > 0 ) {
						?><div class="left arrow">
				            <a href="?p=photo&amp;id=<?php
							echo $photos[ $pivot - 1 ]->Id;
							?>" class="nav"><img src="images/previous.jpg" alt="Προηγούμενη" title="Προηγούμενη" class="hoverclass" /></a>
				        </div><?php
					}
					if ( $pivot + 1 < count( $photos ) && count( $photos ) > 1 ) {
				        ?><div class="right arrow">
				            <a href="?p=photo&amp;id=<?php
							echo $photos[ $pivot + 1 ]->Id;
							?>" class="nav"><img src="images/next.jpg" alt="Επόμενη" title="Επόμενη" class="hoverclass" /></a>
				        </div><?php
					}
			        ?><ul><?php	
						$water->Trace( 'pivot is: ' . $pivot );
						//die( 'pivot is ' . $pivot );
						if ( $pivot > 0 ) {
							for ( $i = 0; $i < $pivot ; ++$i ) {
								?><li><span><a href="?p=photo&amp;id=<?php
								echo $photos[ $i ]->Id;
								?>"><?php
								Element( 'image' , $photos[ $i ] , IMAGE_CROPPED_100x100, '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' , false , 0 , 0 );
								?></a></span></li><?php
							}
						}
						?><li class="selected"><?php
							Element( 'image' , $photos[ $pivot ] , IMAGE_CROPPED_100x100, '' , $photos[ $pivot ]->Name , $photos[ $pivot ]->Name , '' , false , 0 , 0 );
						?></li><?php
						if ( $pivot < 12 ) {						
							for ( $i = $pivot + 1; $i < count( $photos ); ++$i ) {
								?><li><span><a href="?p=photo&amp;id=<?php
								echo $photos[ $i ]->Id;
								?>"><?php
								Element( 'image' , $photos[ $i ] , IMAGE_CROPPED_100x100 , '' , $photos[ $i ]->Name , $photos[ $i ]->Name , '' , false , 0 , 0 );
								?></a></span></li><?php
							}
						}
					?></ul><?php
				?></div><?php
			}
			?><div class="comments"><?php
			if ( $user->HasPermission( PERMISSION_COMMENT_CREATE ) || $xc_settings[ 'anonymouscomments' ] ) {
				Element( 'comment/reply' );
			}
			if ( $image->Numcomments > 0 ) {
				$finder = New CommentFinder();
				if ( $commentid == 0 ) {
					$comments = $finder->FindByPage( $image , $offset , true );
				}
				else {
					$speccomment = New Comment( $commentid );
					$comments = $finder->FindNear( $image , $speccomment );
					$offset = $comments[ 0 ];
					$comments = $comments[ 1 ];
				}
				Element( 'comment/list' , $comments , 0 , 0 );
				?><div class="pagifycomments"><?php
					Element( 'pagify' , $offset , 'photo&id=' . $image->Id , $image->Numcomments , 50 , 'offset' );
				?></div><?php
			}
			?></div>
		</div><div class="eof"></div><?php
	}
?>
