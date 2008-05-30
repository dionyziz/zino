<?php
	
	function ElementAlbumSmall( $album , $creationmockup = false ) {
		global $water;
		global $xc_settings;

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
							Element( 'image' , $mainimage , IMAGE_PROPORTIONAL_210x210 , '' , $albumname , $albumname , '' );
						}
						else {
                            ?><img src="<?php
                            echo $xc_settings[ 'imagesurl' ];
							?>/anonymous130.jpg" alt="<?php
                            echo $albumname;
                            ?>" title="<?php
                            echo $albumname;
                            ?>" style="width:130px;height:130px" /><?php
						}
		        	
		        	?></span>
		            <span class="desc"><?php
					echo $albumname;
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
		        	<span class="albummain"><img src="<?php
                    echo $xc_settings[ 'imagesurl' ];
                    ?>/anonymous130.jpg" alt="Νέο album" title="Νέο album" style="width:130px;height:130px" /></span>
		        </a>
				<span class="desc">
					<input type="text" />
				</span>
			</div><?php
		}
	}
?>
