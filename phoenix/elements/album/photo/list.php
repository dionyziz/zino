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
				Element( 'album/photo/upload' , $album->Id );
			}
		?></div>
		<div class="eof"></div><?php
	}
?>
