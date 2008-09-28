<?php
	function UnitAlbumsMainimage( tInteger $albumid , tInteger $photoid , tCoalaPointer $node ) {
		global $libs;
		global $user;
		global $water;
		global $xc_settings;
		
        $albumid = $albumid->Get();
        $photoid = $photoid->Get();
        
		$libs->Load( 'albums' );
		$libs->Load( 'image/image' );
		$album = New album( $albumid );
		if ( $album->UserId() == $user->Id() ) {
			if ( $photoid != 0 ) {
				$photo = New Image( $photoid );
				if ( $photo->UserId() == $user->Id() ) {
					$album->UpdateMainImage( $photo->Id() );
					$propsize = $photo->ProportionalSize( 100 , 100 );
					?>var newimage = document.createElement( 'img' );
					newimage.src = "<?php
					echo $xc_settings[ 'imagesurl' ];
					echo $photo->UserId();
					?>/<?php
					echo $photoid;
					?>?resolution=<?php
					echo $propsize[ 0 ];
					?>x<?php
					echo $propsize[ 1 ];
					?>";
					newimage.style.width = "<?php
					echo $propsize[ 0 ];
					?>px";
					newimage.style.height = "<?php
					echo $propsize[ 1 ];
					?>px";
					var newlink = document.createElement( 'a' );
					var masterdiv = <?php
					echo $node;
					?>;
					if ( masterdiv ) {
						var masterdivchilda = masterdiv.getElementsByTagName( 'a' );
						var mainimagelink = masterdivchilda[ 2 ];
						masterdiv.insertBefore( newlink , mainimagelink );
						masterdiv.removeChild( mainimagelink );
					}
					var albumid = <?php 
					echo w_json_encode( $albumid );
					?>;
					var photoid = <?php
					echo w_json_encode( $photoid );
					?>;
					if ( AlbumMainImage != 0 ) { // AlbumMainImage is a global variable
						var newmasterdiv = MainImageNode; // MainImageNode is a global variable
						if ( newmasterdiv ) {
							var newmasterdivchilda = newmasterdiv.getElementsByTagName( 'a' );
							var thelink = newmasterdivchilda[ 2 ];
							
							var newlink = document.createElement( 'a' );
							newlink.href = '';
							newlink.onclick = (function ( albumid , photoid , node ) {
								return function() {
									Albums.MainImage( albumid , photoid , node );
									return false;
								}
								})( albumid , AlbumMainImage , newmasterdiv );
							newlink.alt = 'Ορισμός προεπιλεγμένης φωτογραφίας album';
							newlink.title = 'Ορισμός προεπιλεγμένης φωτογραφίας album';
							newlink.className = 'editinfosmainimg';
							newlink.style.display = 'inline';
							var mainimg = document.createElement( 'img' );
							mainimg.src = '<?php
							echo $xc_settings[ 'staticimagesurl' ];
							?>
							icons/vcard.png';
							
							newlink.appendChild( mainimg );
							newlink.style.opacity = 0;
							
							Animations.Create( newlink, 'opacity', 2000, 0, 1, Interpolators.Sin );
							
							newmasterdiv.insertBefore( newlink , thelink );
						}
					}
					MainImageNode = masterdiv;
					<?php
				}
			}
			else {
				$album->UpdateMainImage( 0 );
				?>var newimage = document.createElement( 'img' );
				newimage.src = '<?php
				echo $xc_settings[ 'staticimagesurl' ];
				?>
				anonymousalbum.jpg';
				newimage.style.width = "100px";
				newimage.style.height = "61px";<?php
			}
			?>
			newimage.className = 'articleicon';
			var adiv= document.getElementById( 'smallheader' );
			var adivchilddiv = adiv.getElementsByTagName( 'div' );
			var thediv = adivchilddiv[ 0 ];
			var thedivimglist = thediv.getElementsByTagName( 'img' );
			var theimg = thedivimglist[ 0 ];
			thediv.removeChild( theimg );
			var thedivh2list = thediv.getElementsByTagName( 'h2' );
			var theh2 = thedivh2list[ 0 ];
			thediv.insertBefore( newimage , theh2 );
			
			AlbumMainImage = photoid;<?php
		}
	}
?>
