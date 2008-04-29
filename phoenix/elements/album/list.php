<?php
	
	function ElementAlbumList( tString $username ) {
		global $water;
		
		$username = $username->Get();
		//$subdomain = $subdomain->Get();
		$finder = New UserFinder();
		if ( $name != '' ) {
			$theuser = $finder->FindByName( $username );
		}
		if ( $theuser === false ) {
			return Element( '404' );
		}
		$finder = New AlbumFinder();
		$albums = $finder->FindByUser( $theuser );
		$water->Trace( 'album number: ' . count( $theuser->Albums ) );
		Element( 'user/sections', 'album' );
		?><ul class="albums"><?php
			foreach ( $albums as $album ) {
				?><li><?php
				Element( 'album/small' );
				?></li><?php
			}
			for ( $i = 0; $i < 3; ++$i ) {
				?><li><?php
					Element( 'album/small' );
				?></li><?php
			}
		?></ul>
		<div class="eof"></div><?php
	}
?>
