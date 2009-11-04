<?php
    function ActionStorePurchase( 
        tInteger $itemid, tBoolean $glossy,
        tText $mobile, tText $firstname, tText $lastname,
        tText $postcode, tText $address, tText $addressnum, tText $area,
        tInteger $placeid, tText $size ) {
        global $user;
        global $libs;
        
        $libs->Load( 'store' );
        $libs->Load( 'user/profile' );
        $libs->Load( 'rabbit/helpers/email' );
        
        if ( !$user->Exists() ) {
            ?>Please login first.<?php
            return; // require login
        }
        
        $purchasefinder = New StorePurchaseFinder();
        $purchases = $purchasefinder->FindByItemId( $item->Id );
        
        foreach ( $purchases as $purchase ) {
            if ( $purchase->User->Id == $user->Id ) {
                ?>Ευχαριστούμε! Η παραγγελία σου έχει ολοκληρωθεί.<?php
                return; // only one per user
            }
        }
        
        $itemid = $itemid->Get();
        $glossy = $glossy->Get(); // TODO: get other properties
        $size = $size->Get();
        $mobile = $mobile->Get();
        $postcode = $postcode->Get();
        $address = $address->Get();
        $addressnum = $addressnum->Get();
        $area = $area->Get();
        $placeid = $placeid->Get();
        $firstname = $firstname->Get();
        $lastname = $lastname->Get();
        
        $user->Profile->Area = $area;
        $user->Profile->Address = $address;
        $user->Profile->Addressnum = $addressnum;
        $user->Profile->Postcode = $postcode;
        $user->Profile->Mobile = $mobile;
        $user->Profile->Placeid = $placeid;
        $user->Profile->Firstname = $firstname;
        $user->Profile->Lastname = $lastname;
        $user->Profile->Save();
        
        $item = New StoreItem( $itemid );
        if ( !$item->Exists() ) {
            ?>Store item does not exist.<?php
            return;
        }
        
        $purchase = New StorePurchase();
        $purchase->Itemid = $itemid;
        $purchase->Userid = $user->Id;
        //$purchase->Save();
        
        $propertyfinder = New StorePropertyFinder();
        $properties = $propertyfinder->FindByItemId( $itemid );
        $desired = array();
        if ( $glossy ) {
            $desired[ "glossy" ] = 'yes';
        }
        else {
            $desired[ "glossy" ] = 'no';
        }

        if ( $size == "M" || $size == "L" || $size == "S" || $size == "XL" || $size == "XS" || $size == "XXL" ) {
                $desired[ "Size" ] = $size;
        }
        else {
                $desired[ "Size" ] = "M";
        }
        die( var_dump( count( $properties ) ) );
        foreach ( $properties as $property ) {
            if ( $property->Value == $desired[ $property->Type ] ) {
                $purchaseproperty = New StorePurchaseProperty();
                $purchaseproperty->Propertyid = $property->Id;
                $purchaseproperty->Purchaseid = $purchase->Id;
                die( var_dump( $property->Value ) );
                //$purchaseproperty->Save();
                break;
            }
        }
        
        ob_start();
        $subject = Element( 'store/mail/purchased', $purchase );
        $text = ob_get_clean();
        if ( !empty( $user->Profile->Email ) ) {
            Email( $user->Name, $user->Profile->Email, $subject, $text, "Zino", "info@zino.gr" );
        }
        
        return Redirect( 'store.php?p=thanks&itemid=' . $itemid );
    }
?>
