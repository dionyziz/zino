<?php
	
	function ElementAlbumPhotoList( tInteger $id ) {
		global $page;
		global $user;
		
		$album = new Album( $id );
		
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
			?></dl>
			<ul><?php
				for ( $i = 0; $i < 11; ++$i ) {
					?><li><?php
						Element( 'album/photo/small', false , true, true );
					?></li><?php
				}
			?></ul>
		</div>
		<div class="eof"></div><?php
	}
?>
