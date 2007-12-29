<?php
	function ElementAlbumPhotoSmall( $showfav = false, $showcomnum = false ) {
		//will take max width and height as parameters
		//showfav is for showing favourites
		//showcommnum is for showing comments number
		
		global $page;
		
		$page->AttachStyleSheet( 'css/album/photo/small.css' );
		
		?><div class="photo">
			<a href="">
				<img src="http://static.zino.gr/phoenix/mockups/ph9.jpg" alt="Ουρανοξύστης 9" title="Ουρανοξύστης 9" /><br />
				και δίπλα σε ποτάμι
			</a><?php
			if ( $showfav || $showcommnum ) {
				?><div><?php
					if ( $showfav ) {
						?><span class="addfav"><a href=""><img src="http://static.zino.gr/phoenix/heart_add.png" alt="Προσθήκη στα αγαπημένα" title="Προσθήκη στα αγαπημένα" /></a></span><?php
					}
					if ( $showcommnum ) {
						?><span class="commentsnum">87</span><?php
					}
				?></div><?php
			}
		?></div><?php
	}
?>