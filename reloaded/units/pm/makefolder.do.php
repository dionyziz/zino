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
	$( newfolder ).attr( { id : '<?php
	echo $folderid;
	?>' , alt : <?php
	echo $foldername;
	?> , title : <?php
	echo $foldername;
	?> } ).addClass( 'folder' ).addClass( 'top' );
	newfolder.id = 'folder_<?php
	echo $folderid;
	?>';
	var newfolderhref = document.createElement( 'a' );
	$( newfolderhref ).attr( { href : '' } ).addClass( 'folderlinks' ).click( function( folder , folderid ) {
		pms.ShowFolderPm( newfolder , <?php
		echo $folderid;
		?> );
	}).append( document.createTextNode( <?php
	echo $foldername;
	?> ) );
	$( newfolder ).append( newfolderhref );
	newfolderlink.parentNode.insertBefore( newfolder , newfolderlink );
	pms.activefolder = newfolder;
	pms.CancelNewFolder();
	pms.ShowFolderPm( newfolder , <?php
	echo $folderid;
	?> );<?php
}
?>