<?php
    class ElementStoreManager extends Element {
        public function Render( tInteger $id ) {

            global $user;
	        if( ! $user->HasPermission( PERMISSION_ADMINPANEL_VIEW ) ) {
	            ?> Permission Denied <?php
	            return;
	        }

            global $libs;
            $libs->Load( 'store' );
            $libs->Load( 'user/profile' );
            $id = $id->Get(); 
            $sifinder = New StoreitemFinder();
            $storeitem = $sifinder->FindById( $id );

            ?><h2>Αγορές</h2>
                <table class="stats">
                <tr>
                    <th>Χρήστης</th>
                    <th>Ημερομηνία</th>
                    <th>Πόλη - Περιοχή</th>
                    <th>Διεύθυνση</th>
                    <th>Ονοματεπώνυμο</th>
                    <th>Κινητό</th>
                </tr>
            <?php

            $prcfinder = New StorepurchaseFinder();
            $purchases = $prcfinder->FindByItemId( $id );

            foreach ( $purchases as $purchase ) {
                if ( $purchase->User->Exists() && $purchase->User->Profile->Exists() ) {
                        ?><tr><td><?php
                        echo $purchase->User->Name;
                    ?></td><td><?php
                        echo $purchase->Created;
                    ?></td><td><?php
                        echo $purchase->User->Profile->Location->Name;
                    ?> - <?php
                        echo htmlspecialchars( $purchase->User->Profile->Area );
                    ?></td><td><?php
                        echo htmlspecialchars( $purchase->User->Profile->Address );
                    ?> <?php
                        echo htmlspecialchars( $purchase->User->Profile->Addressnum );
                    ?> - <?php
                        echo htmlspecialchars( $purchase->User->Profile->Postcode );
                    ?></td><td><?php
                        echo htmlspecialchars( $purchase->User->Profile->Firstname );
                    ?> <?php
                        echo htmlspecialchars( $purchase->User->Profile->Lastname );
                    ?></td><td><?php
                        echo htmlspecialchars( $purchase->User->Profile->Mobile );
                    ?></td></tr><?php
                }
            }
            ?></table><?php
        }
    }
?>
