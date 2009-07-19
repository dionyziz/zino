<?php
    function UnitFavouritesAddStore( tInteger $itemid, tCoalaPointer $f ) {
        global $libs;
        global $user;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'favourite' );
        
        $favourite = New Favourite();
        $favourite->Itemid = $itemid->Get();
        $favourite->Typeid = $typeid->Get();
        $favourite->Save();
        
        echo $f;
        ?>( <?php
        ob_start();
        ?><a href="http://<?php
        echo $user->Subdomain;
        ?>.zino.gr/" title="<?php
        echo $user->Name;
        ?>"><?php
        Element( 'image/view', $user->Avatarid, $user->Id, 50, 50, IMAGE_CROPPED_100x100, '', $user->Name, '', false, 0, 0, 0 );
        ?></a><?php
        echo w_json_encode( ob_get_clean() );
        ?> );<?php
    }
?>
