<?php
	
	function ElementAlbumPhotoList( tInteger $id ) {
		global $page;
		global $user;
		global $rabbit_settings; 
		global $water;
		
		$album = new Album( $id->Get() );
		
		$page->SetTitle( $album->Name );
		Element( 'user/sections', 'album' , $album->User );
		?><div id="photolist">
			<h2><?php
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
			if ( $album->User->Id == $user->Id ) {
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
				for ( $i = 0; $i < 11; ++$i ) {
					?><li><?php
						Element( 'album/photo/small', false , true, true );
					?></li><?php
				}
			?></ul><?php
			Element( 'album/photo/upload' , $album->Id );
			/*
			<div class="newpic">
				<iframe src="index.php?p=upload&amp;albumid=<?php
					echo $album->Id;
					?>" frameborder="no" scrolling="no" id="upload">
				</iframe>
			</div>
			*/
			?>
		</div>
		<div class="eof"></div><?php
	}
?>
