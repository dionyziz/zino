<?php
	
	function ElementAlbumPhotoSmall( $showdesc = false, $showfav = false, $showcomnum = false ) {
		?><div class="photo">
			<a href="">
				<img src="http://static.zino.gr/phoenix/mockups/ph9.jpg" alt="Ουρανοξύστης 9" title="Ουρανοξύστης 9" /><?php
				if ( $showdesc ) {
					?><br />και δίπλα σε ποτάμι<?php
				}
			?></a><?php
			if ( $showfav || $showcommnum ) {
				?><div><?php
					if ( $showfav ) {
						?><span class="addfav"><a href=""><img src="http://static.zino.gr/phoenix/heart_add.png" alt="Προσθήκη στα αγαπημένα" title="Προσθήκη στα αγαπημένα" /></a></span><?php
					}
					if ( $showcomnum ) {
						?><span class="commentsnum">87</span><?php
					}
				?></div><?php
			}
		?></div><?php
	}
?>
