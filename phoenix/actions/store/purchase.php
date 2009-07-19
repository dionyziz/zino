<?php
    function ActionStorePurchase( 
        tInteger $itemid, tBoolean $glossy,
        tText $mobile, 
        tText $postcode, tText $address, tText $addressnum, tText $area,
        tInteger $placeid ) {
        global $user;
        global $libs;
        
        $libs->Load( 'store' );
        
        if ( !$user->Exists() ) {
            return; // require login
        }
        
        $itemid = $itemid->Get();
        $glossy = $glossy->Get(); // TODO: get other properties
        $mobile = $mobile->Get();
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
        
        $item = New StoreItem( $itemid );
        if ( !$item->Exists() ) {
            return;
        }
        
        $purchase = New StorePurchase();
        $purchase->Itemid = $itemid;
        $purchase->Userid = $user->Id;
        $purchase->Save();
        
        $propertyfinder = New StorePropertyFinder();
        $properties = $propertyfinder->FindByItemId( $itemid );
        if ( $glossy ) {
            $desired = 'yes';
        }
        else {
            $desired = 'no';
        }
        foreach ( $properties as $property ) {
            if ( $property->Value = $desired ) {
                $purchaseproperty = New StorePurchaseProperty();
                $purchaseproperty->Propertyid = $property->Id;
                $purchaseproperty->Purchaesid = $purchase->Id;
                $purchaseproperty->Save();
                break;
            }
        }
        
        ob_start();
        $subject = Element( 'store/mail/purchased', $purchase );
        $text = ob_get_clean();
        Email( $user->Name, $user->Profile->Email, $subject, $text, "Zino", "info@zino.gr" );
        
        return Redirect( 'store.php?p=thanks' );
    }
?>
