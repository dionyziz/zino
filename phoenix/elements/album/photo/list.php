<?php
	function ElementAlbumPhotoList() {
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/list.css' );
		
		Element( 'user/sections' );
		?><div id="photolist">
			<h2>Ουρανοξύστες</h2>
			<dl>
				<dt class="photonum">29 φωτογραφίες</dt>
				<dt class="commentsnum">328 σχόλια</dt>
			</dl>
			<ul><?php
				for ( $i = 0; $i < 11; ++$i ) {
					?><li><?php
						Element( 'album/photo/small' );
					?></li><?php
				}
			?></ul>
		</div>
		<div class="eof"></div><?php
	}
?>