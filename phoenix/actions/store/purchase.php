<?php
    function ActionStorePurchase( 
        tInteger $itemid, tBoolean $glossy,
        tText $mobile, 
        tText $postcode, tText $address, tText $addressnum, tText $area,
        tInteger $placeid ) {
        global $user;
        
        if ( !$user->Exists() ) {
            return; // require login
        }
        
        $itemid = $itemid->Get();
        $glossy = $glossy->Get(); // TODO: get other properties
        $phone = $phone->Get();
        $postcode = $postcode->Get();
        $address = $address->Get();
        $addressnum = $addressnum->Get();
        $area = $area->Get();
        $placeid = $placeid->Get();
        
        $user->Profile->Area = $area;
        $user->Profile->Address = $address;
        $user->Profile->Addressnum = $addressnum;
        $user->Profile->Postcode = $postcode;
        $user->Profile->Mobile = $mobile;
        $user->Profile->Placeid = $placeid;
        $user->Profile->Save();
    }
?>
