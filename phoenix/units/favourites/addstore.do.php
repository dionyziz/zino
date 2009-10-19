<?php
    function UnitFavouritesAddStore( tInteger $itemid, tCoalaPointer $f ) {
        global $libs;
        global $user;
        
        if ( !$user->Exists() ) {
            return;
        }
        
        $libs->Load( 'favourite' );
        $libs->Load( 'image/image' );
        
        $favourite = New Favourite();
        $favourite->Itemid = $itemid->Get();
        $favourite->Typeid = TYPE_STOREITEM;
        $favourite->Save();
        
        echo $f;
        ?>( <?php
        ob_start();
        ?><a href="http://<?php
        echo $user->Subdomain;
        ?>.zino.gr/" title="<?php
        echo $user->Name;
        ?>"><?php
        Element( 'user/avatar', $user->Avatarid, $user->Id, 50, 50,$user->Name, IMAGE_CROPPED_100x100 );
        //Element( 'image/view', $user->Avatarid, $user->Id, 50, 50, IMAGE_CROPPED_100x100, '', $user->Name, '', true, 50, 50, 0 );
        ?></a><?php
        echo w_json_encode( ob_get_clean() );
        ?> );<?php
    }
?>
