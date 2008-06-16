<?php
    function UnitPmShowfolder( tInteger $folderid ) {
    	global $libs;
    	global $user;
    	
    	$libs->Load( 'pm/pm' );	
    	
    	$folderid = $folderid->Get();
        $folder = New PMFolder( $folderid );

        if ( $folder->Userid != $user->Id ) {
            return;
        }

    	?>var deletelink = document.getElementById( 'deletefolderlink' );
    	var renamelink = document.getElementById( 'renamefolderlink' );

        deletelink.style.display = 'block';
        deletelink.onclick = ( function( folderid ) {
            return function() {
                pms.DeleteFolder( folderid );
                return false;
            }
        })( <?php 
        echo $folderid;
        ?> );
        renamelink.style.display = 'block';
        renamelink.onclick = ( function( folderid ) {
            return function () {
                pms.RenameFolder( folderid );
                return false;
            }
        } )( <?php
        echo $folderid;
        ?> );
        pms.messagescontainer.innerHTML = <?php
        ob_start();
        Element( 'pm/folder/view', $folder );
        echo w_json_encode( ob_get_clean() );
        ?>;
        pms.ShowFolderNameTop( <?php
        ob_start();
        Element( 'pm/folder/name', $folder );
        echo w_json_encode( ob_get_clean() );
        ?> );<?php

        ?>pms.DragPm2();<?php
    }
?>
