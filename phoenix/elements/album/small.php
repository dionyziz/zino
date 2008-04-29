<?php
	
	function ElementAlbumSmall( $album ) {
		global $water;
		
		$commentsnum = $album->Numcomments;
		$photonum = $album->Numphotos;
		?><div class="album">
			<a href="" onclick="return false;">
	        	<span class="albummain">
	        		<img src="http://static.zino.gr/phoenix/mockups/apartments.jpg" alt="Φωτογραφίες" title="<?php
					echo htmlspecialchars( $album-Name );
					?>" />
	        	</span>
	            <span class="desc"><?php
				echo htmlspecialchars( $album->Name );
				?></span>
	        </a>
	        <dl><?php
				if ( $photonum > 0 ) {
		            ?><dt><img src="http://static.zino.gr/phoenix/imagegallery.png" alt="Φωτογραφίες" title="Φωτογραφίες" /></dt>
		            <dd><?php
					echo $photonum;
					?></dd><?php
				}
				if ( $commentsnum > 0 ) {
					?><dt><img src="http://static.zino.gr/phoenix/comment.png" alt="Σχόλια" title="Σχόλια" /></dt>
					<dd><?php
					echo $commentsnum;
					?></dd><?php
				}
	        ?></dl>
		</div><?php
	}
?>
