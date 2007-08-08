<?php
	function ElementUserAlbums( $theuser , $allalbums ) {
		global $water;
		global $page;
		global $libs;
		global $user;
		global $xc_settings;
		
		$libs->Load( 'albums' );
		$page->AttachStyleSheet( 'css/rounded.css' );
		$page->AttachStyleSheet( 'css/albums.css' );
		$page->AttachScript( 'js/albums.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachScript( 'js/animations.js' );
		//$allalbums = Albums_RetrieveUserAlbums( $theuser->Id() );
		?><div class="content">
			<div id="albumcontainer" class="register" style="display:block;overflow:hidden;width:900px;padding-left:0;"><?php
                $i = 0;
				foreach ( $allalbums as $album ) {
					Element( 'album/small' , $album , $theuser , false );
                    if (++$i == 3) {
                        $i = 0;
                        ?><div style="clear:both"></div><?php
                    }
				}
				?></div>
		</div><?php

		if ( $theuser->Id() == $user->Id() ) {
			?><br /><br />
			<a href="?p=faqc&amp;id=28" style="display: inline;">
				<img src="<?php
					echo $xc_settings[ 'staticimagesurl' ];
				?>icons/help.png" alt="Πληροφορίες για τα albums" style="width: 16px; height: 16px; opacity: 0.5;" onmouseover="this.style.opacity=1;g( 'commenthelp' ).style.visibility='visible';" onmouseout="this.style.opacity=0.5;g( 'commenthelp' ).style.visibility='hidden';" />
			</a>
			<a href="javascript:Albums.ExpandCreateAlbum();" id="createalbumlink" class="albumlinks">Δημιουργία album&#187;</a>
			<div class="newalbum" id="newalbum" style="display:none">
				<div class="content">
					<div class="register">
						<div class="opties" style="width:370px;">
							<div class="upperline">
								<div class="leftupcorner"></div>
								<div class="rightupcorner"></div>
								<div class="middle"></div>
							</div>
							<div class="rectanglesopts" style="padding:10px;">
								<span class="directions">
									Τίτλος
								</span><br />
								<span class="tip">
									(δώσε τον τίτλο που θες να έχει το album σου)
								</span><br />
								<input type="text" tabindex="1" style="width:300px;" id="albumtitle" maxlength="100" /><br /><br />
								<span class="directions">
									Περιγραφή
								</span><br />
								<span class="tip">
									(γράψε λίγα λόγια που να περιγράφουν το περιεχόμενο του album σου)
								</span><br />
								<input type="text" tabindex="1" style="width:300px;" id="albumdescription" maxlength="200" /><br /><br />
								<a href="javascript:Albums.CreateAlbum();" class="albumlinks">Δημιουργία&#187;</a>
							</div>
							<div class="downline">
								<div class="leftdowncorner"></div>
								<div class="rightdowncorner"></div>
								<div class="middledowncss"></div>
							</div>
						</div>
					</div>
				</div>
			</div><?php
		}
	}
?>