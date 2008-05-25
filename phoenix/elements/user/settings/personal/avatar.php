<?php

	function ElementUserSettingsPersonalAvatar() {
		global $user;
		global $libs;
		
		//$libs->Load( 'image' );
		Element( 'user/avatar' , $user , 150 , '' , '' );
		?><div class="changeavatar">
		<a href="" onclick="Settings.ShowAvatarChange();return false;">Αλλαγή εικόνας</a>
		</div>
		<div class="avatarlist" id="avatarlist">
			<h3>Επέλεξε μια φωτογραφία</h3><?php
			$egoalbum = New Album( $user->Egoalbumid );
			$finder = New ImageFinder();
			?><ul><?php	
			$images = $finder->FindByAlbum( $egoalbum , 0 , $egoalbum->Numphotos );
			foreach ( $images as $image ) {	
				$size = $image->ProportionalSize( 100 , 100 );
				?><li><a href="" onclick="alert( 'test' );return false;"<?php
				Element( 'image' , $image , $size[ 0 ] , $size[ 1 ] , 'photosmall' , $image->Name , $image->Name , '' );
				?></li>
				</a><?php
			}
			?></ul>
			<a href="" onclick="Modals.Destroy();return false;" class="button">
				Ακύρωση
			</a>
		</div><?php
	}
?>
