<?php
	function ElementAlbumSmall( $album , $theuser ) {
		global $water;
		global $page;
		global $user;
		global $libs;
		global $xc_settings;
        
		$libs->Load( 'albums' );
		$libs->Load( 'image/image' );
		$page->AttachStyleSheet( 'css/rounded.css' );
		$page->AttachStyleSheet( 'css/albums.css' );
		$page->AttachScript( 'javascript' , 'js/albums.js' );
        $dummy = false;
		if ( $dummy ) {
			$mainimage = 0;
			$name = '';
			$description = '';
			$albumid = 0;
		}
		else {
			$mainimage = $album->MainImage();
			$name = htmlspecialchars( $album->Name() );
			$description = htmlspecialchars( $album->Description() );
			$albumid = $album->Id();
			$albumuserid = $album->UserId();
			$photonum = $album->PhotosNum();
			$commentsnumber = $album->CommentsNum();
			$pageviews = $album->Pageviews();
		}
		?><div id="album<?php
			echo $albumid;
			?>" class="opties" style="float:left;overflow:hidden;margin-right:20px;margin-top:10px;margin-bottom:10px;width:242px;<?php
			if ( $dummy ) {
				?>display:none;<?php
			}
			?>"><div>
				<div class="upperline">
					<div class="leftupcorner"></div>
					<div class="rightupcorner"></div>
					<div class="middle"></div>
				</div>
				<div class="rectangleopts mainalbum" style="height:255px;width:220px;_width:236px;padding:10px;_position:relative;left:3px">
					<div class="albumshow">
						<div style="text-align:center;">
							<div>
								<a href="index.php?p=album&amp;id=<?php
								echo $albumid;
								?>" class="enteralbum">
									<span style="display:none"><?php
										echo $name;
									?></span>
									<span class="albumname"><?php
										if ( strlen( $name ) > 22 ) {
											echo htmlspecialchars( utf8_substr( $album->Name() , 0 , 22 ) );
											?>...<?php
										}
										else { 
											echo $name;
										}
									?></span>
								</a><?php
								if ( $albumuserid == $user->Id() ) {
									?><a href="" onclick="Albums.EditListAlbum( '<?php
									echo $albumid; 
									?>' , '0' );return false;" class="editinfos"><img src="<?php
                                    echo $xc_settings[ 'staticimagesurl' ];
                                    ?>icons/edit.png" alt="Επεξεργασία ονόματος" title="Επεξεργασία ονόματος" /></a><?php
								}
								if ( $albumuserid == $user->Id() || $user->CanModifyCategories() ) {
									?><a href="" onclick="Albums.DeleteAlbum( '<?php
									echo $albumid;
									?>' );return false;" class="editinfos"><img src="<?php
                                    echo $xc_settings[ 'staticimagesurl' ];
                                    ?>icons/delete.png" alt="Διαγραφή album" title="Διαγραφή album" /></a><?php
								} ?>
							</div><a href="index.php?p=album&amp;id=<?php
							echo $albumid;
							?>" class="enteralbum" title="<?php
							echo $name;
							?>" alt="<?php
							echo $name;
							?>"><?php
							if ( $mainimage == 0 ) {
								?><img src="<?php
                                echo $xc_settings[ 'staticimagesurl' ];
                                ?>anonymousalbum.jpg" /><?php
							}
							else {
								$thismainimage = New Image( $mainimage );
								$dimensions = $thismainimage->ProportionalSize( 208 , 127 );
								$style = 'width:'.$dimensions[ 0 ].'px;height:'.$dimensions[ 1 ].'px;';
								Element( 'image' , $thismainimage , $dimensions[ 0 ] , $dimensions[ 1 ] , '' , $style , $name , $name );
							}
							?>
							</a>
						</div>
						<div class="albuminfo" style="text-align:center;">
							<span style="display:none"><?php
								echo $description;
							?></span>
							<span><?php
							if ( trim( $description ) != '' ) {
								if ( strlen( $description ) > 120 ) {
									echo htmlspecialchars( utf8_substr( $album->Description() , 0 , 120 ) );
									?>...<?php
								}
								else {
									echo $description;
								}
							}
							if ( trim( $description ) == '' && ( $user->Id() == $albumuserid ) ) {
								?>-Δεν έχεις ορίσει περιγραφή-<?php
							}
							?></span><?php
							if ( $albumuserid == $user->Id() ) {
								?><a href="" onclick="Albums.EditListAlbum( '<?php
								echo $albumid;
								?>' , '1' );return false;" class="editinfos"><img src="<?php
                                echo $xc_settings[ 'staticimagesurl' ];
                                ?>icons/edit.png" alt="Επεξεργασία περιγραφής" title="Επεξεργασία περιγραφής" /></a><?php
							}
						?></div><br />
						<div class="albuminfo"><?php
							if ( $photonum > 0 ) {
								echo $photonum;
								if ( $photonum == 1 ) {
									?> φωτογραφία<?php
								}
								else {
									?> φωτογραφίες<?php
								}
								?><br /><?php
							}
							if ( $commentsnumber > 0 ) {
								echo $commentsnumber;
								if ( $commentsnumber == 1 ) {
									?> σχόλιο<?php
								}
								else {
									?> σχόλια<?php
								}
								?><br /><?php
							}
							if ( $pageviews > 0 ) {
								echo $pageviews;
								if ( $pageviews == 1 ) {	
									?> προβολή<?php
								}
								else {
									?> προβολές<?php
								}
							}
						?></div>
					</div>
					
				</div>
				<div class="downline">
					<div class="leftdowncorner"></div>
					<div class="rightdowncorner"></div>
					<div class="middledowncss"></div>
				</div>
			</div>
		</div><?php
	}
?>