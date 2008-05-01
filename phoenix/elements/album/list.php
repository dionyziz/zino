<?php
	
	function ElementAlbumList( tString $username ) {
		global $page;
		
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
		if ( $theuser === false ) {
			return Element( '404' );
		}
		$finder = New AlbumFinder();
		$albums = $finder->FindByUser( $theuser );
		Element( 'user/sections', 'album' , $theuser );
		?><ul class="albums"><?php
			foreach ( $albums as $album ) {
				?><li><?php
				Element( 'album/small' , $album , false );
				?></li><?php
			}
			?><li class="create">
				<a href="" class="new"><img src="http://static.zino.gr/phoenix/add.png" />Δημιουργία album</a>
			</li>
		</ul>
		<div class="creationmakeup"><?php
			Element( 'album/small' , false , true );
		?></div>
		<div class="eof"></div><?php
	}
?>
