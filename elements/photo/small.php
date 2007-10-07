<?php
	function ElementPhotoSmall( $photo , $album , $offset ) {
		global $user;
		global $page;
		global $xc_settings;
        
		$photoname = NoExtensionName( htmlspecialchars( $photo->Name() ) );
		$photodescription = htmlspecialchars( $photo->Description() );
		//$page->AttachScript( 'js/photos.js' );
		$photocommentsnum = $photo->NumComments();
		$photopageviews = $photo->Pageviews();
		
		++$photopageviews;
		
		if ( $photocommentsnum == 1 ) {
			$photocommentstext = " σχόλιο,";
		}
		else { 
			$photocommentstext = " σχόλια,";
		}
		if ( $photopageviews == 1 ) {
			$photopageviewstext = " προβολή";
		}
		else {
			$photopageviewstext = " προβολές";
		}
		$dimensions = $photo->ProportionalSize( 210 , 210 );
		?>
		<div class="photoview" id="photo<?php
		echo $photo->Id();
		?>">
			<a href="index.php?p=photo&amp;id=<?php
			echo $photo->Id();
			?>&amp;lstoffset=<?php
			echo $offset;
			?>" class="enterphoto">
				<span style="display:none"><?php
					echo $photoname;
				?></span>
				<span class="albumname"><?php
					if ( strlen( $photoname ) > 18 )  {
						echo htmlspecialchars( utf8_substr( $photo->Name() , 0 , 18 ) );
					}
					else {
						echo $photoname;
					}
				?></span>
			</a><?php
			if ( $photo->UserId() == $user->Id() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
				?><a href="" onclick="Photos.EditListPhoto( this.parentNode, '<?php
				echo $photo->Id();
				?>' , '0' );return false;" class="editinfos" alt="Επεξεργασία ονόματος" title="Επεξεργασία ονόματος"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/edit.png" /></a> 
				<?php
				if ( $album->MainImage() != $photo->Id() ) {
					?><a href="" onclick="Albums.MainImage( '<?php
					echo $album->Id();
					?>' , '<?php
					echo $photo->Id();
					?>' , this.parentNode );return false;" class="editinfosmainimg" alt="Ορισμός προεπιλεγμένης φωτογραφίας album" title="Ορισμός προεπιλεγμένης φωτογραφίας album"><img src="<?php
                    echo $xc_settings[ 'staticimagesurl' ];
                    ?>icons/vcard.png" /></a><?php
				}
				else {
					?><a></a><?php
				}
			}
			if ( $photo->UserId() == $user->Id() || $user->CanModifyCategories() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
				?><a href="" onclick="Photos.DeletePhoto( this.parentNode , '<?php
				echo $photo->Id();
				?>' , '<?php
				echo $album->Id();
				?>' );return false;" class="editinfos" alt="Διαγραφή φωτογραφίας" title="Διαγραφή φωτογραφίας"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/delete.png" /></a><?php
			}
			?><br /><a href="index.php?p=photo&amp;id=<?php
			echo $photo->Id();
			?>&amp;lstoffset=<?php
			echo $offset;
			?>" class="enterphoto" title="<?php
			echo $photoname;
			?>" alt="<?php
			echo $photoname;
			?>"><?php
			$style = 'width:'.$dimensions[ 0 ].'px;height:'.$dimensions[ 1 ].'px;';
			Element( 'image' , $photo , $dimensions[ 0 ] , $dimensions[ 1 ] , '' , $style , $photo->Name() , $photo->Name() );
			/*
			<img src="image.php?id=<?php
			echo $photo->Id();
			?>&amp;width=<?php
			echo $dimensions[ 0 ];
			?>&amp;height=<?php
			echo $dimensions[ 1 ];
			?>" style="width:<?php
			echo $dimensions[ 0 ];
			?>px;height:<?php
			echo $dimensions[ 1 ];
			?>px;" />
			*/?>
			</a>
			<br />
			<span style="display:none"><?php
				if ( trim( $photodescription ) == '' && $photo->UserId() == $user->Id() ) {
					?>-Δεν έχεις ορίσει περιγραφή-<?php
				}
				else {
					echo $photodescription;
				}
			?></span>
			<span class="photodescription"><?php
				if ( trim( $photodescription ) != '' ) {
					if ( strlen( $photodescription ) > 65 ) {
						echo htmlspecialchars( utf8_substr( $photo->Description() , 0 , 65 ) );
					}
					else {
						echo $photodescription;
					}
				}
				if ( trim( $photodescription ) == '' && $photo->UserId() == $user->Id() ) {
					?>-Δεν έχεις ορίσει περιγραφή-<?php
				}
				?>
			</span><?php
			if ( $photo->UserId() == $user->Id() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
    			?><a href="" onclick="Photos.EditListPhoto( this.parentNode , '<?php
    			echo $photo->Id();
    			?>' , '1' );return false;" class="editinfos" alt="Επεξεργασία περιγραφής" title="Επεξεργασία περιγραφής"><img src="<?php
                echo $xc_settings[ 'staticimagesurl' ];
                ?>icons/edit.png" /></a><?php
			}
			?><div>
				<span class="photodetail"><?php
				if ( $photocommentsnum > 0 ) {
					echo $photocommentsnum.$photocommentstext;
				}
				?></span>
				<span class="photodetail"> <?php
				echo $photopageviews.$photopageviewstext;
				?></span>
			</div><?php
	?></div>
	<?php
	}	
?>
