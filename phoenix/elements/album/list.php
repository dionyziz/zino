<?php
	function ElementAlbumList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/album/list.css' );
		
		Element( 'user/sections', 'album' );
		?><ul class="albums"><?php
			for ( $i = 0; $i < 3; ++$i ) {
				?><li><?php
					Element( 'album/small' );
				?></li><?php
			}
		?></ul><?php
	}
?>