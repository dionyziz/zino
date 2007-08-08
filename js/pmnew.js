var pms = {
	activefolder : 0,
	node : 0,
	ShowFolder : function( folder ) {
		//this function just shows the content of a folder when the user clicks on one
		if ( pms.activefolder == 0 ) {
			pms.node = document.getElementById( 'firstfolder' );
			pms.activefolder = pms.node;
		}
		if ( pms.activefolder != pms.node ) {
			pms.activefolder.className = 'folder top';
		}
		else {
			pms.activefolder.className = 'folder';
		}
		if ( folder != pms.node ) {
			folder.className = 'activefolder top';
		}
		else {
			folder.className = 'activefolder';
		}
		pms.activefolder = folder;
	}

}