<?php
    class ElementStoreProduct extends Element {
        public function Render( tString $name ) {
            global $libs;
            global $page;
            
            $page->SetTitle( 'ZinoSTORE' );
            $libs->Load( 'store' );
            $libs->Load( 'favourite' );
            $name = $name->Get();
            
            $page->AttachScript( 'js/store.js' );
            
            switch ( $name ) {
                case 'necklace':
                    $item = New StoreItem( 20 );
                    break;
                default:
                    return Element( '404' );
            }
            
            $purchasefinder = New StorePurchaseFinder();
            $purchases = $purchasefinder->FindByItemId( $item->Id );
            
            $favouritefinder = New FavouriteFinder();
            $loves = $favouritefinder->FindByEntity( $item );
            
            ?>
            <h1>
                <div class="city">
                    <div class="cityend1">
                    </div>
                </div>
                <span>
                    <a href="http://www.zino.gr/"><img src="http://static.zino.gr/phoenix/logo-trans.png" alt="Zino" /></a>
                    <a href="http://store.zino.gr/"><img src="http://static.zino.gr/phoenix/store/store.png" alt="STORE" /></a>
                </span>
            </h1>
            <a class="back" href="http://www.zino.gr/">πίσω στο zino</a>
            <div class="content">
                <div class="productimage">
                    <img src="http://static.zino.gr/phoenix/store/necklace.jpg" alt="Necklace φυσαλίδα" />
                </div>
                <div class="productdetails">
                    <h2>Necklace φυσαλίδα <span><img src="http://static.zino.gr/phoenix/store/15euros.png" alt="15€" /></span></h2>
                    <ul class="toolbox">
                        <li class="lurv"><a href="" onclick="return false;">Το αγαπώ</a></li>
                        <li class="wantz"><a href="" onclick="return false;">Το θέλω</a></li>
                    </ul>
                    <div class="description">
                        <p>
                            Η φυσαλίδα του Zino διατίθεται σε 
                            ένα σχέδιο από πολυμορφικό πηλό για το λαιμό, 
                            είτε είσαι αγόρι είτε κορίτσι, στο χρώμα του 
                            Zino και με λευκό περίγραμμα.
                        </p><p>
                            To σχέδιο είναι συλλεκτικό και διατίθεται στον περιορισμένο 
                            αριθμό των <strong>32 κομματιών</strong>.
                            Αφού πουληθούν όλα τα κομμάτια, το συγκεκριμένο σχέδιο δεν θα ξαναβγεί.
                            Κάθε ένα από τα 32 κομμάτια είναι 
                            χειροποίητο από την καλλιτέχνιδα <a href="http://toothfairy-creations.zino.gr/">Gardy</a>.
                        </p><p class="please">
                            <strong>Το Zino σ' αγαπάει:</strong> Η προσφορά σου είναι ζωτικής σημασίας 
                            για εμάς. Τα έσοδα από την αγορά είναι απαραίτητα για να 
                            συνεχίσει να ζει το Zino. Τα χρήματα θα διατεθούν για την 
                            βελτίωση και διατήρηση του Zino.
                        </p>
                    </div>
                </div>
                <div class="eof"></div>
                <h3 class="lurv">Το αγαπάνε:</h3>
                <ul class="lurv"><?php
                    foreach ( $loves as $love ) {
                        ?><li><a href="http://<?php
                            echo $love->User->Subdomain;
                            ?>.zino.gr/" title="<?php
                            echo $love->User->Name;
                            ?>">
                            <?php
                            Element( 'image/view', $love->User->Avatarid, $love->Userid, 50, 50, IMAGE_CROPPED_100x100, '', $love->User->Name, '', false, 0, 0, 0 );
                        ?></a></li><?php
                    }
                ?></ul>
                <h3 class="wantz">Το έχουν:</h3>
                <ul class="wantz"><?php
                    foreach ( $purchases as $purchase ) {
                        ?><li><a href="http://<?php
                            echo $purchase->User->Subdomain;
                            ?>.zino.gr/" title="<?php
                            echo $purchase->User->Name;
                            ?>">
                            <?php
                            Element( 'image/view', $purchase->User->Avatarid, $purchase->Userid, 50, 50, IMAGE_CROPPED_100x100, '', $purchase->User->Name, '', false, 0, 0, 0 );
                        ?></a></li><?php
                    }
                ?></ul>
                <p class="remain">
                    ...απομένουν <?php
                    echo $item->Remaining();
                    ?> συλλεκτικά κομμάτια.
                </p>
            </div>
            <?php
        }
    }
?>
