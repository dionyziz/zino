<?php
	function ElementAlbumView( tInteger $id, tInteger $offset ) {
		global $page;
		global $libs;
		global $water;
		global $user;
		global $xc_settings;
		
        $id = $id->Get();
        $offset = $offset->Get();
        
		$libs->Load( 'albums' );
		$libs->Load( 'image/image' );
		
		if ( !ValidId( $id ) ) {
			Element( '404' );
			return;
		}
		
		$album = New Album( $id );
		
		if ( !$album->Exists() || $album->IsDeleted() ) {
			$page->SetTitle( 'Το album δεν βρέθηκε' );
			?>Το album δεν βρέθηκε<?php
			return;
		}
		
		$page->SetTitle( $album->Name() );
		
        $page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/animations.js' );
		$page->AttachScript( 'js/photos.js' );
		$page->AttachScript( 'js/albums.js' );
		$page->AttachScript( 'js/albumview.js' );
		
		$page->AttachStyleSheet( 'css/rounded.css' );
		$page->AttachStyleSheet( 'css/images_list.css' );
		$page->AttachStyleSheet( 'css/photos.css' );
		
        if ( !ValidId( $offset ) ) {
            $offset = 1;
        }
		
		/*
		$pages = intval( $albumphotosnumber / 16 );
		$remainder = $albumphotosnumber % 16;
		if ( ( $remainder != 0 ) || ( $remainder == 0 && $pages == 0 ) ) {
			++$pages;
		}
		*/
		
		$photos = Albums_RetrieveImages( $album->Id() , $offset , 16 );
				
		?><div id='myalbumid' style="display:none;"><?php
		echo $id;
		?></div><div class="body"><?php
			
			Element( 'album/header', $album );
			
			?><div style="clear:left; margin-top: 40px; margin-bottom: 30px;" />
			<div class="content" id="content"><?php
			
				foreach ( $photos as $photo ) {
					Element( 'photo/small', $photo, $album, $offset );
				}
				
			?></div>
			<div style="clear:both;" />
			<div style="text-align: center;"><?php
				if ( $album->PhotosNum() > 16 ) {
					Element( 'pagify' , $offset, 'album&amp;id=' . $album->Id(), $album->PhotosNum(), 16 );
				}
			?></div><?php
				if ( $album->PhotosNum() == 0 ) { // here the number of photos for the album is needed
					?><span id="nophotos">Το album δεν περιέχει φωτογραφίες</span><br /><br /><?php
				}
			?><a href="user/<?php
				echo $album->Creator()->Username();
			?>?viewingalbums=yes" class="photolinks" style="margin-bottom: 20px; margin-top: 20px;">&#171;Επιστροφή στο προφίλ</a><br /><br /><?php
				if ( $user->Id() == $album->UserId() && $user->Rights() >= $xc_settings[ "allowuploads" ] ) {
                    /*
					?><small>Προσωρινά έχει περιοριστεί η δημιουργία εικόνων για τεχνικούς λόγους. Δοκιμάστε ξανά σε λίγες ώρες.</small><?php
                    */
					?><a href="" onclick="Photos.Newphoto( this ); return false;" class="photolinks" id="newphotolink">Νέα φωτογραφία&#187;</a>
					<br />
					<div class="iframecontainer" id="newphoto" style="display:none">
						<iframe src="index.php?p=uploadframe&amp;albumid=<?php
						echo $album->Id();
						?>" frameborder="no" class="photoiframe" scrolling="no" id="iesucks3">
						</iframe>
					</div><?php
				}
		?></div>
		<div style="display: none;" id="album_photosnum"><?php
			echo $album->PhotosNum();
		?></div>
		<div style="display: none;" id="album_mainimage"><?php
			echo $album->MainImage();
		?></div><?php

		$album->AddPageview(); // add 1 pageview to the album	
	}
?>
