<?php
	function ElementAlbumHeader( $album ) {
		global $water;
		global $page;
		global $user;
        global $xc_settings;
        
		// $album is an instance of the album class
		
		$page->AttachStyleSheet( 'css/article.css' );
		$page->AttachScript( 'js/albums.js' );
		if ( $album->MainImage() != 0 ) {
			$mainimage = New Image( $album->MainImage() );
			$propsize = $mainimage->ProportionalSize( 100 , 100 );
		}
		$albumname = htmlspecialchars( $album->Name() );
		$albumcreator = $album->Creator();
		$albumcreatorname = htmlspecialchars( $albumcreator->Username() );
		$albumphotosnumber = $album->PhotosNum();
		$albumcommentsnumber = $album->CommentsNum();
		$albumpageviews = $album->Pageviews();
		
    	?><div class="article" id="smallheader">
    		<div class="header"><?php
    			if ( $album->MainImage() != 0 ) {
					$style = 'width:'.$propsize[ 0 ].'px;height:'.$propsize[ 1 ].'px;';
					Element( 'image' , $mainimage , $propsize[ 0 ] , $propsize[ 1 ] , 'articleicon' , $style , $albumname , $albumname );
				/*
				<img src="image.php?id=<?php
    				echo $mainimage->Id();
    				?>&amp;width=<?php
    				echo $propsize[ 0 ];
    				?>&amp;height=<?php
    				echo $propsize[ 1 ];
    				?>" alt="<?php
    				echo $albumname;
    				?>" title="<?php
    				echo $albumname;
    				?>" style="width:<?php
    				echo $propsize[ 0 ];
    				?>px;height:<?php
    				echo $propsize[ 1 ];
    				?>px;" class="articleicon"/><?php
				*/
    			}
    			else {
    				?><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>anonymousalbum.jpg" style="width:100px;height:61px;" alt="<?php
    				echo $albumname;
    				?>" title="<?php
    				echo $albumname;
    				?>" class="articleicon" /><?php
    			}
    			?><h2><?php 
    			echo $albumname; 
    			?></h2><?php
    			if ( $user->Id() == $album->UserId() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
    				?><a href="" onclick="Albums.EditSmallAlbum( <?php
    				echo $album->Id();
    				?> , 0 );return false;" alt="Επεξεργασία ονόματος" title="Επεξεργασία ονόματος" class="editinfos"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/edit.png" /></a><?php
    			}
    			?>
    			<div>
    				<small><?php
    					if ( trim( $album->Description() ) != '' ) {
    						?><span class="details" style="font-size:9pt;"><?php
    						echo htmlspecialchars( $album->Description() );
    						?></span><?php
    					}
    					if ( trim( $album->Description() ) == '' && $user->Id() == $album->UserId() ) {
    						?><span class="details" style="font-size:9pt;">-Δεν έχεις ορίσει περιγραφή-</span><?php
    					}
    					if ( $user->Id() == $album->UserId() ) {
    						?><a href="" onclick="Albums.EditSmallAlbum( <?php
    						echo $album->Id();
    						?> , 1 );return false;" alt="Επεξεργασία περιγραφής" title="Επεξεργασία περιγραφής" class="editinfos"><img src="<?php
							echo $xc_settings[ 'staticimagesurl' ];
							?>icons/edit.png" /></a><?php
    					}
    					Element( "user/icon" , $album->Creator() , true , true );
    					?>
    					<span class="description">από <?php
    						Element( "user/static", $album->Creator() );
    						?><span class="details">, πριν από <?php echo dateDistance( $album->Date() ); ?></span>
    					</span>
    					<span class="details" style="float:left;">
    						<span style="font-size:9pt;" id="photonumber"><?php
    						if ( $albumphotosnumber > 0 ) {
    							echo $albumphotosnumber;
    							if ( $albumphotosnumber == 1 ) {
    								?> φωτογραφία<?php
    							}
    							else {
    								?> φωτογραφίες<?php
    							}
    							?>, <?php
    						}
    						?></span>
    						<span style="font-size:9pt;"><?php 
    						if ( $albumcommentsnumber > 0 ) {
    							echo $albumcommentsnumber;
    							if ( $albumcommentsnumber == 1 ) {
    								?> σχόλιο<?php
    							}
    							else { 
    								?> σχόλια<?php
    							} 
    							?>, <?php
    						}
    						?></span><span style="font-size:9pt;"><?php 
    							echo ++$albumpageviews;
    							if ( $albumpageviews == 1 ) { 
    								?> προβολή <?php
    							}
    							else { 
    								?> προβολές <?php
    							} 
    						?></span>
    					</span>
    				</small>
    				<br />
    			</div>
    			<br />
    		</div>
    	</div>
    	<?php
	}
?>
