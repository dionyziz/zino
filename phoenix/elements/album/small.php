<?php
	
	function ElementAlbumSmall( $album , $creationmockup = false ) {
		global $water;
		
		if ( !$creationmockup ) {
			$commentsnum = $album->Numcomments;
			$photonum = $album->Numphotos;
			if ( $album->Id == $album->User->Egoalbumid ) {
				$albumname = 'Φωτογραφίες μου';
			}
			else {
				$albumname = htmlspecialchars( $album->Name );
			}
			?><div class="album">
				<a href="?p=album&amp;id=<?php
				echo $album->Id;
				?>">
		        	<span class="albummain"><?php //150 130
						if ( $album->Mainimage > 0 ) {	
							$mainimage = New Image( $album->Mainimage );
							$size = $mainimage->ProportionalSize( 150 , 130 );
							Element( 'image' , $mainimage , $size[ 0 ] , $size[ 1 ] , '' , $albumname , $albumname , '' );
						}
						else {
							Element( 'image' , 'anonymous130.jpg' , 130 , 130 , '' , $albumname , $albumname , '' );
						}
		        	
		        	?></span>
		            <span class="desc"><?php
					if ( $album->User->Egoalbumid == $album->Id ) {
						?>Φωτογραφίες μου<?php
					}
					else {
						echo $albumname;
					}
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
		else {
			?><div class="album createalbum">
				<a href="">
		        	<span class="albummain">
		        		<img src="http://static.zino.gr/phoenix/mockups/apartments.jpg" alt="Νέο album" title="Νέο album" />
		        	</span>
		        </a>
				<span class="desc">
					<input type="text" />
				</span>
			</div><?php
		}
	}
?>
