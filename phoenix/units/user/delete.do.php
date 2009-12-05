<?php
    function UnitUserDelete( tText $password ) {
        global $user;

        if ( !$user->Exists() ) {
            return false;
        }

        $password = $password->Get();
        if ( !$user->IsCorretPassword( $password ) ) {
			?>$( '#deletemodal div div span' ).find( 'div div span' ).fadeIn( 300 );
            document.body.style.cursor = 'default';
            <?php
        }

        $user->Deleted = 1;
        $user->Save();

        ?>window.location.reload( true );<?php
    }
?>
