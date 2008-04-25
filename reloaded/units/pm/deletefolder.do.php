<?php
    function UnitPmDeletefolder( tInteger $folderid ) {
        global $user;
        global $libs;

        $libs->Load( 'pm' );
        $folderid = $folderid->Get();
        $folder = new PMFolder( $folderid );
        if ( $folder->UserId != $user->Id() ) {
            return;
        }
        /*var foldertodelete = $( '#folder_<?php
        echo $folderid;
        ?>' )[ 0 ];
		*/?>
		$( '#folder_<?php 
		echo $folderid;
		?>' ).animate( { opacity : '0' , height : '0' } , 700 , function() {
			$( this ).remove();
			if ( !pms.writingnewpm ) {
				pms.ShowFolderPm( $( '#firstfolder' )[ 0 ] , -1 );
			}
		} );
		/*
        Animations.Create( foldertodelete , 'opacity' , 1500 , 1 , 0 );
        Animations.Create( foldertodelete , 'height' , 600 , foldertodelete.offsetHeight , 0 , function() {
            foldertodelete.parentNode.removeChild( foldertodelete );
            if ( !pms.writingnewpm ) {
                pms.ShowFolderPm( document.getElementById( 'firstfolder' ) , -1 );
            }
        } );
		*/<?php
        $folder->Delete();
    }
?>