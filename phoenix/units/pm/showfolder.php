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
    	var renamelink = document.getElementById( 'renamefolderlink' );<?php

        if ( $folder->Typeid == PMFOLDER_USER ) {
            ?>deletelink.style.display = 'block';
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
            echo w_json_encode( $folder->Name );
            ?> );<?php
        }
        else {
            ?>pms.messagescontainer.innerHTML = <?php
            ob_start();
            Element( 'pm/folder/view', $folder );
            echo w_json_encode( ob_get_clean() );
            ?>;
            deletelink.style.display = 'none';
            deletelink.onclick = function() {
                return false;
            };
            renamelink.style.display = 'none';
            renamelink.onclick = function () {
                return false;
            };
            pms.ShowFolderNameTop( '<?php 
            if ( $folder->Typeid == PMFOLDER_INBOX ) {
                ?>Εισερχόμενα' );<?php
                $unreadmsgs = $user->Count->Unreadpms;
                if ( $unreadmsgs > 0 ) {
                    ?>pms.UpdateUnreadPms( <?php
                    echo $unreadmsgs;
                    ?> );<?php
                }
            }
            else {
                ?>Απεσταλμένα' );<?php
            }
        }
    }
?>
