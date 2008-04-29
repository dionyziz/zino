<?php
	
	function ElementAlbumSmall( $album ) {
		global $water;
		
		//$commentsnum = $album->Numcomments;
		//$photonum = $album->Numphotos;
		$water->Trace( 'commentsnum: ' . $album->Numcomments );
		$water->Trace( 'photonum: ' . $album->Numphotos );
		?><div class="album">
			<a href="" onclick="return false;">
	        	<span class="albummain">
	        		<img src="http://static.zino.gr/phoenix/mockups/apartments.jpg" alt="Φωτογραφίες" />
	        	</span>
	            <span class="desc"><?php
				echo htmlspecialchars( $album->Name );
				?></span>
	        </a>
	        <dl>
	            <dt><img src="http://static.zino.gr/phoenix/imagegallery.png" alt="Φωτογραφίες" /></dt>
	            <dd>20</dd>
	            <dt><img src="http://static.zino.gr/phoenix/comment.png" alt="Σχόλια" /></dt>
	            <dd>184</dd>
	        </dl>
		</div><?php
	}
?>
