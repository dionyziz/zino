<?php
    
    function UnitFavouritesAdd( tInteger $itemid , tInteger $typeid ) {
        global $libs;
        
        die( 'Breakpoint 104' );

        $libs->Load( 'favourite' );
        
        $favourite = New Favourite();
        $favourite->Itemid = $itemid->Get();
        $favourite->Typeid = $typeid->Get();
        $favourite->Save();
    }
?>
