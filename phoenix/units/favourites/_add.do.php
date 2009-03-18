<?php
    
    function UnitFavouritesAdd( tInteger $itemid , tInteger $typeid ) {
        global $libs;
        
        $libs->Load( 'favourite' );
        
        $favourite = New Favourite();
        $favourite->Itemid = $itemid->Get();
        $favourite->Typeid = $typeid->Get();
        //$favourite->Save();
        ?>alert( '<?php
            Element( 'user/name', $favourite->User->Id, $favourite->User->Name, $favourite->User->Subdomain, true );
        ?>' );<?php
    }
?>
