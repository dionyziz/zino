<?php
function UnitPmMakefolder( tString $foldername ) {
	global $user;
	global $libs;
	
	$libs->Load( 'pm' );
	$foldername = $foldername->Get();
	$folder = new PMFolder();
	$folder->Name = $foldername;
	$folder->UserId = $user->Id();
	$folder->Save();
	$folderid = $folder->Id;
	$foldername = w_json_encode( $foldername );
	?>var newfolderlink = document.getElementById( 'newfolderlink' );
	var newfolder = document.createElement( 'div' );
	newfolder.id = 'folder_<?php
	echo $folderid;
	?>';
	newfolder.className = 'folder top';
	newfolder.alt = <?php
	echo $foldername;
	?>;
	newfolder.title = <?php
	echo $foldername;
	?>;
	var newfolderhref = document.createElement( 'a' );
	newfolderhref.href = '';
	newfolderhref.onclick = ( function( folder , folderid ) {
		return function() {
			pms.ShowFolderPm( folder , folderid );
			return false;
		}
	})( newfolder , <?php
	echo $folderid;
	?> );
	newfolderhref.className = 'folderlinks';
	newfolderhref.appendChild( document.createTextNode( <?php
	echo $foldername;
	?> ) );
	newfolder.appendChild( newfolderhref );
	newfolderlink.parentNode.insertBefore( newfolder , newfolderlink );
	pms.activefolder = newfolder;
	pms.CancelNewFolder();
	pms.ShowFolderPm( newfolder , <?php
	echo $folderid;
	?> );<?php
}
?>