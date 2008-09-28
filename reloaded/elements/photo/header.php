<?php
	function ElementPhotoHeader( $photo , $oldcomments ) {
		global $page;
		global $user;
		global $water;
		global $libs;
		global $xc_settings;
        
		$libs->Load( 'albums' );
		$libs->Load( 'image/image' );
		$page->AttachStyleSheet( 'css/article.css' );
		$photoname = htmlspecialchars( NoExtensionName( $photo->Name() ) );
		$photocreator = $photo->Creator();
		$photocomments = $photo->NumComments();
		$photopageviews = $photo->Pageviews();
		$album = New album( $photo->AlbumId() );
		++$photopageviews;
		?><div class="article">
			<div class="header">
				<h2><?php
				echo $photoname;
				?></h2><?php
				if ( $user->Id() == $photo->UserId() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
					?><a href="" onclick="Photos.EditSmallPhoto( <?php
					echo $photo->Id();
					?> , 0 , this.parentNode );return false;" alt="Επεξεργασία ονόματος" title="Επεξεργασία ονόματος" class="editinfos"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/edit.png" /></a><?php
				}
				?><div>
					<small><?php
						if ( trim( $photo->Description() ) != '' ) {
							?><span class="details" style="font-size:9pt;"><?php
							echo htmlspecialchars( $photo->Description() );
							?></span><?php
						}
						if ( trim( $photo->Description() ) == '' && $user->Id() == $photo->UserId() ) {
							?><span class="details" style="font-size:9pt;">-Δεν έχεις ορίσει περιγραφή-</span><?php
						}
						if ( $user->Id() == $photo->UserId() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
							?><a href="" onclick="Photos.EditSmallPhoto( <?php
							echo $photo->Id();
							?> , 1 , this.parentNode );return false;" alt="Επεξεργασία περιγραφής" title="Επεξεργασία περιγραφής" class="editinfos"><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/edit.png" /></a><?php
						}
						Element( "user/icon" , $photo->Creator() , true , true );
						?>
						<span class="description" style="font-size:9pt;">από <?php
							Element( "user/static", $photo->Creator() );
							?><span class="details">, πριν από <?php echo dateDistance( $photo->Date() ); 
							?><br />στο album <a href="index.php?p=album&amp;id=<?php
							echo $album->Id();
							?>&amp;offset=1"><?php
							echo htmlspecialchars($album->Name());
							?></a></span>
						</span>
						<span class="details" style="float:left;">
							<span id="numcomments" style="font-size:9pt;"><?php
							if ( $photocomments > 0 ) {
								echo $photocomments;
								if ( $photocomments == 1 ) {
									?> σχόλιο<?php
								}
								else {
									?> σχόλια<?php
								}
								//if ( $oldcomments !== -1 ) {
									if ( $photocomments > 50 && $oldcomments === false ) {
										?> <a href="?p=photo&amp;id=<?php
										echo $photo->Id();
										?>&amp;oldcomments=yes">(προβολή όλων)</a><?php
									}
									else if ( $photocomments > 50 ) {
										?> <a href="?p=photo&amp;id=<?php
										echo $photo->Id();
										?>">(προβολή μόνο νεότερων)</a><?php
									}
									?>, <?php
								//}
							}
							?></span>
							<span style="font-size:9pt;"><?php
							echo $photopageviews;
							if ( $photopageviews == 1 ) {
								?> προβολή<?php
							}
							else {
								?> προβολές<?php
							}
							?></span>
						</span>
					</small>
				</div>
			</div>
		</div><?php
	}
?>
