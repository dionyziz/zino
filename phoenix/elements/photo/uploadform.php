<?php
	function ElementPhotoUploadForm( tInteger $albumid ) {
		global $page;
		global $water;
        
        $albumid = $albumid->Get();
		$page->AttachScript( 'js/photos.js' );
		$page->AttachStyleSheet( 'css/rounded.css' );
		$page->AttachStyleSheet( 'css/images.css' );
		$water->Disable();
		?>
		<div class="content" id="iesucks2">
			<div class="register">
				<div class="opties" style="width:405px;">
					<div class="upperline">
						<div class="leftupcorner"></div>
						<div class="rightupcorner"></div>
						<div class="middle"></div>
					</div>
					<div class="rectanglesopts">
						<form method="post" enctype="multipart/form-data" action="do/image/upload2" id="iesucks">
							<input type="hidden" name="albumid" value="<?php
							echo $albumid;
							?>" />
							<input type="file" name="uploadimage" onchange="Photos.UploadPhoto( this );" /><br />
							<span class="upltip"><?php
							if ( $albumid != 0 ) {
								?>(επέλεξε την εικόνα που θες να δημοσιεύσεις στο album σου, πρέπει να είναι της μορφής .jpg , . gif ή .png και να μην ξεπερνάει το 1ΜΒ)<?php
							}
							else {
								?>(επέλεξε την εικόνα που θες να ανεβάσεις για να βάλεις στο άρθρο σου)<?php
							}
							?></span>
							<input type="submit" value="upload" style="display:none" />
						</form>
					</div>
					<div class="downline">
						<div class="leftdowncorner"></div>
						<div class="rightdowncorner"></div>
						<div class="middledowncss"></div>
					</div>
				</div>
			</div>
		</div>
		<img src="images/ajax-loader.gif" style="display:none;" />
        <?php
        
        return array( 'tiny' => true );
	}
?>
