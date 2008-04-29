<?php
	
	function ElementAlbumList( tString $username ) {
		global $page;
		
		$username = $username->Get();
		//$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $username != '' ) {
			$theuser = $finder->FindByName( $username );
			$page->SetTitle( $username . " Albums" );
		}
		if ( $theuser === false ) {
			return Element( '404' );
		}
		$finder = New AlbumFinder();
		$albums = $finder->FindByUser( $theuser );
		$water->Trace( 'album number: ' . count( $theuser->Albums ) );
		Element( 'user/sections', 'album' , $theuser );
		?><ul class="albums"><?php
			foreach ( $albums as $album ) {
				?><li><?php
				Element( 'album/small' , $album , false );
				?></li><?php
			}
		?></ul>
		<div class="creationmakeup"><?php
			Element( 'album/small' , false , true );
		?></div>
		<div class="create">
			<a href="" class="new"><img src="http://static.zino.gr/phoenix/add.png" />Δημιουργία album</a>
		</div>
		<div class="eof"></div><?php
	}
?>
