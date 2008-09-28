<?php
	function UnitPmFolderRename( tInteger $folderid, tText $newname ) {
		global $user;
		global $libs;
		
		$libs->Load( 'pm/pm' );
		
		$folderid = $folderid->Get();
		$newname = $newname->Get();
		
		$folder = New PMFolder( $folderid );
		if ( !$folder->Exists() ) {
			return;
		}
		if ( $folder->Userid != $user->Id ) {
			return;
		}
		$folder->Name = $newname;
		$folder->Save();
	}
?>
