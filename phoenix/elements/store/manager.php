<?php
    class ElementStoreManager extends Element {
        public function Render( tInteger $id ) {

            global $libs;
            $libs->Load( 'store' );
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
                ?><tr><td><?php
                    echo $purchase->User->Name;
                ?></td></tr><?php
            }
            ?></table><?php
        }
    }
?>
