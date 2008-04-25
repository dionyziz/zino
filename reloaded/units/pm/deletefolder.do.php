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
		/*$( '#folder_<?php 
		echo $folderid;
		?>' ).animate( { opacity : '0' , height : '0' } , 700 , function() {
			$( this ).remove();
			if ( !pms.writingnewpm ) {
				pms.ShowFolderPm( $( '#firstfolder' )[ 0 ] , -1 );
			}
		} );<?php
		*/
        $folder->Delete();
    }
?>