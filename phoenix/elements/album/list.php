<?php
	
	function ElementAlbumList( tString $username , tInteger $page ) {
		global $page;
		global $user;
		global $rabbit_settings;
		global $water;
		
		$username = $username->Get();
		//$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $username != '' ) {
			$theuser = $finder->FindByName( $username );
			if ( strtoupper( substr( $username, 0, 1 ) ) == substr( $username, 0, 1 ) ) {
				$page->SetTitle( $username . " Albums" );
			}
			else {
				$page->SetTitle( $username . " albums" );
			}
		}
		if ( !isset( $theuser ) || $theuser === false ) {
			return Element( '404' );
		}
		$finder = New AlbumFinder();
		$albums = $finder->FindByUser( $theuser , ( $page - 1 )*12 , 12 );
		$water->Trace( 'username: '. $theuser->Name );
		Element( 'user/sections', 'album' , $theuser );
		?><ul class="albums"><?php
			foreach ( $albums as $album ) {
				?><li><?php
				Element( 'album/small' , $album , false );
				?></li><?php
			}
			if ( $theuser->Id == $user->Id ) {
				?><li class="create">
					<a href="" class="new"><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>add3.png" alt="Δημιουργία album" title="Δημιουργία album" />Δημιουργία album</a>
				</li><?php
			}
		?></ul><?php
		if ( $theuser->Id == $user->Id ) {
			?><div class="creationmakeup"><?php
				Element( 'album/small' , false , true );
			?></div>
			<div class="creating">
				<img src="<?php
				echo $rabbit_settings[ 'imagesurl' ];
				?>ajax-loader.gif" alt="Παρακαλώ περιμένετε" title="Παρακαλώ περιμένετε" /> Δημιουργία album
			</div><?php
		}
		?><div class="pagify"><?php
		Element( $page->Get() , '?p=albums&username=' . $theuser->Subdomain , $theuser->Count->Albums , 12 , 'page' );
		?></div><div class="eof"></div><?php
	}
?>
