<?php

	function ElementUserSettingsPersonalAvatar() {
		global $user;
		global $libs;
		
		//$libs->Load( 'image' );
		Element( 'user/avatar' , $user , 150 , 'avie' , '' );
		?><div class="changeavatar">
		<a href="" onclick="Settings.ShowAvatarChange();return false;">Αλλαγή εικόνας</a>
		</div>
		<div class="avatarlist" id="avatarlist">
			<h3>Επέλεξε μια φωτογραφία</h3>
			<div class="uploaddiv">
				<object data="?p=upload&amp;albumid=<?php
				echo $user->Egoalbum;
				?>" class="uploadframe" id="uploadframe" type="text/html">
				</object>
			</div><?php
			$egoalbum = New Album( $user->Egoalbumid );
			$finder = New ImageFinder();
			?><ul><?php	
			$images = $finder->FindByAlbum( $egoalbum , 0 , $egoalbum->Numphotos );
			foreach ( $images as $image ) {	
				$size = $image->ProportionalSize( 100 , 100 );
				?><li><a href="" onclick="Settings.SelectAvatar( '<?php
				echo $image->Id;
				?>' );return false;"><?php
				Element( 'image' , $image , $size[ 0 ] , $size[ 1 ] , 'photosmall' , $image->Name , $image->Name , '' );
				?></a></li><?php
			}
			?></ul>
			<div class="cancel">
				<a href="" onclick="Modals.Destroy();return false;" class="button">
				Ακύρωση
				</a>
			</div>
		</div><?php
	}
?>
