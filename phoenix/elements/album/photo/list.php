<?php
	function ElementAlbumPhotoList( tInteger $id , tInteger $offset ) {
		global $page;
		global $user;
		global $rabbit_settings; 
		global $water;
		
		$album = New Album( $id->Get() );
		
		$offset = $offset->Get();
		if ( $offset <= 0 ) {
			$offset = 1;
		}
		
		Element( 'user/sections', 'album' , $album->User );
		?><div id="photolist"><?php
			if ( $album->IsDeleted() ) {
				$page->SetTitle( 'Το album έχει διαγραφεί' ); 
				?>Το album έχει διαγραφεί<?php
			}
			else {
				$finder = New ImageFinder();
				$images = $finder->FindByAlbum( $album , ( $offset - 1 )*20 , 20 );
				w_assert( is_array( $images ), 'FindByAlbum must return an array' );
				$page->SetTitle( $album->Name );
				?><h2><?php
				if ( $album->Id == $album->User->Egoalbumid ) {
					?>Φωτογραφίες μου<?php
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
						Element( 'album/photo/small' , $image , false , true , true );
						?></li><?php
					}
				?></ul>
				<div class="eof"></div>
				<div class="pagifyimages"><?php
				Element( 'pagify' , $offset , 'album&id=' . $album->Id , $album->Numphotos , 20 , 'offset' );
				?></div><?php
			}
		?></div>
		<div class="eof"></div><?php
	}
?>
