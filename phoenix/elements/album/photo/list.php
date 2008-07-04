<?php
	function ElementAlbumPhotoList( tInteger $id , tInteger $pageno ) {
		global $page;
		global $user;
		global $rabbit_settings; 
		global $water;
		
		$album = New Album( $id->Get() );
		
		$pageno = $pageno->Get();
		if ( $pageno <= 0 ) {
			$pageno = 1;
		}
		
		if ( !$album->Exists() ) {
			?>To album δεν υπάρχει<div class="eof"></div><?php
			return;
		}
		
		Element( 'user/sections', 'album' , $album->User );
		?><div id="photolist"><?php
			if ( $album->IsDeleted() ) {
				$page->SetTitle( 'Το album έχει διαγραφεί' ); 
				?>Το album έχει διαγραφεί</div><div class="eof"></div><?php
				return;
			}
			$water->Trace( "egoalbumid " . $album->User->Egoalbumid );
			$water->Trace( "albumid " . $album->Id );
			$finder = New ImageFinder();
			$images = $finder->FindByAlbum( $album , ( $pageno - 1 )*20 , 20 );
			if ( $album->Id == $album->User->Egoalbumid ) {
				if ( strtoupper( substr( $album->User->Name, 0, 1 ) ) == substr( $album->User->Name, 0, 1 ) ) {
					$page->SetTitle( $album->User->Name . " Φωτογραφίες" );
				}
				else {
					$page->SetTitle( $album->User->Name . " φωτογραφίες" );
				}
			}
			else {
				$page->SetTitle( $album->Name );
			}
			?><h2><?php
			if ( $album->Id == $album->User->Egoalbumid ) {
				?>Εγώ<?php
			}
			else {
				echo htmlspecialchars( $album->Name );
			}
			?></h2>
			<dl><?php
				if ( $album->Numphotos > 0 ) {
					?><dt class="photonum"><?php
					echo $album->Numphotos;
					?></dt><?php
				}
				if ( $album->Numcomments > 0 ) {
					?><dt class="commentsnum"><?php
					echo $album->Numcomments;
					?></dt><?php
				}
			?></dl><?php
			if ( $album->User->Id == $user->Id || $user->HasPermission( PERMISSION_ALBUM_DELETE ) ) {
				if ( $album->Id != $user->Egoalbumid ) {
					?><div class="owner">
						<div class="edit"><a href="" onclick="PhotoList.Rename( '<?php
						echo $album->Id;
						?>' );return false;">Μετονομασία</a>
						</div>
						<div class="delete"><a href="" onclick="PhotoList.Delete( '<?php
						echo $album->Id;
						?>' );return false;">Διαγραφή</a></div>
					</div><?php
				}
			}
			if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
				?><div class="uploaddiv">
					<object data="?p=upload&amp;albumid=<?php
					echo $album->Id;
					?>&amp;typeid=0" class="uploadframe" id="uploadframe" type="text/html"></object>
				</div><?php
			}
			?><ul><?php
				foreach( $images as $image ) {
					?><li><?php
					Element( 'album/photo/small' , $image , false , true );
					?></li><?php
				}
			?></ul>
			<div class="eof"></div>
			<div class="pagifyimages"><?php

            $link = '?p=album&id=' . $album->Id . '&pageno=';
            $total_pages = ceil( $album->Numphotos / 20 );
			Element( 'pagify', $pageno, $link, $total_pages );

			?></div>
			</div><div class="eof"></div><?php
	}
?>
