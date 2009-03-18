<?php
    
    function UnitFavouritesAdd( tInteger $itemid , tInteger $typeid ) {
        global $libs;
        global $user;
        
        $libs->Load( 'favourite' );
        
        $favourite = New Favourite();
        $favourite->Itemid = $itemid->Get();
        $favourite->Typeid = $typeid->Get();
        //$favourite->Save();
        ?>alert( '<?php
            Element( 'user/name', $user->Id, $user->Name, $user->Subdomain, true );
        ?>' );<?php
    }
?>
