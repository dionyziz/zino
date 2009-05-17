<?php
    function ActionCoinsReward( tInteger $snuid, tInteger $currency, tInteger $error ) {
        /*
        TODO: Whitelist:
        * 74.205.58.114
        * 99.132.162.242
        * 99.132.162.243
        * 99.132.162.244
        * 99.132.162.245
        */
        global $libs;
        
        $snuid = $snuid->Get();
        $currency = $currency->Get();
        $error = $error->Get();
        
        $libs->Load( 'coins' );
        $coins = New Coins( $snuid );
        if ( !$coins->Exists() ) {
            $coins = New Coins();
            $coins->Userid = $snuid;
        }
        $coins->Amount += $currency;
        $coins->Save();
    }
?>
