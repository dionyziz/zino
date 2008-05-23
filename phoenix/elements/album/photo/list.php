<?php
	function ElementAlbumPhotoList( tInteger $id ) {
		global $page;
		global $user;
		global $rabbit_settings; 
		global $water;
		
		$album = New Album( $id->Get() );
		Element( 'user/sections', 'album' , $album->User );
		?><div id="photolist"><?php
			if ( $album->IsDeleted() ) {
				$page->SetTitle( 'Το album έχει διαγραφεί' ); 
				?>Το album έχει διαγραφεί<?php
			}
			else {
				$finder = New ImageFinder();
				$images = $finder->FindByAlbum( $album );
				w_assert( is_array( $images ), 'FindByAlbum must return an array' );
				$page->SetTitle( $album->Name );
				?><h2><?php
				echo htmlspecialchars( $album->Name );
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
				?><ul><?php
					foreach( $images as $image ) {
						?><li><?php
						Element( 'album/photo/small' , $image , false , true , true );
						?></li><?php
					}
				?></ul><?php
				if ( $album->User->Id == $user->Id && $user->HasPermission( PERMISSION_IMAGE_CREATE ) ) {
					?><a href="" onclick="$( 'form#uploadframe' ).toggle();return false;">Show</a>
					<iframe src="index.php?p=upload&amp;albumid=<?php
					echo $album->Id;
					?>" frameborder="no" class="uploadform" scrolling="no" id="uploadframe">
					</iframe><?php
				}
			}
		?></div>
		<div class="eof"></div><?php
	}
?>
