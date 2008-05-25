<?php

	function ElementUserSettingsPersonalAvatar() {
		global $user;
		global $libs;
		
		//$libs->Load( 'image' );
		Element( 'user/avatar' , $user , 150 , '' , '' );
		?><div class="changeavatar">
		<a href="" onclick="return false;">Αλλαγή εικόνας</a>
		</div>
		<div class="avatarlist" id="avatarlist"><?php
			$egoalbum = New Album( $user->Egoalbumid );
			$finder = New ImageFinder();
			?><ul><?php	
			$images = $finder->FindByAlbum( $egoalbum , 0 , $egoalbum->Numphotos );
			foreach ( $images as $image ) {	
				$size = $image->ProportionalSize( 100 , 100 );
				?><li><?php
				Element( 'image' , $image , $size[ 0 ] , $size[ 1 ] , 'photosmall' , $image->Name , $image->Name , '' );
				?></li><?php
			}
			?></ul>
		</div><?php
	}
?>
