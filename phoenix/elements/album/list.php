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
				Element( 'album/small' , $album );
				?></li><?php
			}
		?></ul>
		<div class="eof"></div><?php
	}
?>
