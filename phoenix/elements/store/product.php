<?php
    class ElementStoreProduct extends Element {
        public function Render( tString $name ) {
            global $libs;
            global $page;
            global $user;
            
            $page->SetTitle( 'ZinoSTORE' );
            
            $libs->Load( 'store' );
            $libs->Load( 'favourite' );
            $libs->Load( 'image/image' );
            $libs->Load( 'place' );
            $libs->Load( 'user/profile' );
            
            $name = $name->Get();
            
            $page->AttachScript( 'js/store.js' );
            $page->AttachInlineScript( 'Store.OnLoad();' );
            
            $storefinder = New StoreItemFinder();
            
            switch ( $name ) {
                case 'necklace':
                    $item = $storefinder->FindByName( 'necklace' );
                    w_assert( $item instanceof StoreItem, 'StoreFinder returned a ' . gettype( $item ) . ', but StoreItem instance was expected' );
                    break;
                default:
                    return Element( '404' );
            }
            
            $purchasefinder = New StorePurchaseFinder();
            $purchases = $purchasefinder->FindByItemId( $item->Id );
            
            $favouritefinder = New FavouriteFinder();
            $loves = $favouritefinder->FindByEntity( $item );
            
            $igot = false;
            $ilove = false;
            if ( $user->Exists() ) {
                foreach ( $purchases as $purchase ) {
                    if ( $purchase->User->Id == $purchase->Id ) {
                        $igot = true;
                        break;
                    }
                }
                
                foreach ( $loves as $love ) {
                    if ( $love->User->Id == $user->Id ) {
                        $ilove = true;
                        break;
                    }
                }
            }
            
            $placefinder = New PlaceFinder();
            $places = $placefinder->FindAll();
            
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
                        <?php
                        if ( $user->Exists() ) {
                            ?><li class="lurv"><a href="" onclick="return false;"<?php
                            if ( $ilove ) {
                                ?> id="luved"<?php
                            }
                            ?>><?php
                            if ( !$ilove ) {
                                ?>Το αγαπώ<?php
                            }
                            ?></a></li><?php
                        }
                        if ( !$igot && $item->Remaining() ) {
                            ?><li class="wantz"><a <?php
                            if ( !$user->Exists() ) {
                                ?>href="http://www.zino.gr/join"<?php
                            }
                            else {
                                ?>href="" onclick="return false;"<?php
                            }
                            ?>>Το θέλω</a></li><?php
                        }
                        ?>
                    </ul>
                    <div class="description">
                        <?php
                        echo $item->Description; // no escaping here
                        ?>
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
                            Element( 'image/view', $love->User->Avatarid, $love->Userid, 50, 50, IMAGE_CROPPED_100x100, '', $love->User->Name, '', true, 50, 50, 0 );
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
            <div id="buynow">
                <form>
                    <h3>Η διεύθυνσή σου</h3>
                    <div>
                        <label for="city">Πόλη:</label>
                        <select name="city" id="city"><?php
                        foreach ( $places as $place ) {
                            ?><option value="<?php
                            echo $place->Id;
                            ?>"<?php
                            if ( $user->Profile->Placeid == $place->Id ) {
                                ?> selected="selected"<?php
                            }
                            ?>><?php
                            echo htmlspecialchars( $place->Name );
                            ?></option><?php
                        }
                        ?></select>
                    </div>
                    <div>
                        <span>Θα σου το αποστείλουμε ταχυδρομικώς. Όλα τα έξοδα μεταφοράς, αποστολής, και αντικαταβολής
                        καλύπτονται δωρεάν από το Zino.</span>
                        
                        <span>Θα επικοινωνήσουμε μαζί σου τηλεφωνικά μέσα στις επόμενες 2 μέρες για την παράδοση από
                        κοντά στην πόλη σου.</span>
                    </div>
                    <div>
                        <label for="address">Οδός:</label><input type="text" name="address" id="address" value="<?php
                        echo htmlspecialchars( $user->Profile->Address );
                        ?>" />
                    </div>
                    <div>
                        <label for="addressnum">Αριθμός:</label><input type="text" name="addressnum" id="addressnum" value="<?php
                        echo htmlspecialchars( $user->Profile->Addressnum );
                        ?>" />
                    </div>
                    <div>
                        <label for="area">Περιοχή:</label><input type="text" name="area" id="area" value="<?php
                        echo htmlspecialchars( $user->Profile->Area );
                        ?>" />
                    </div>
                    <div>
                        <label for="postcode">ΤΚ:</label><input type="text" name="postcode" id="postcode" value="<?php
                        echo htmlspecialchars( $user->Profile->Postcode );
                        ?>" />
                    </div>
                    
                    <h3>Επικοινωνία</h3>
                    <div>
                        <label for="mobile">Το κινητό σου:</label><input type="text" name="mobile" id="mobile" value="<?php
                        echo htmlspecialchars( $user->Profile->Mobile );
                        ?>" />
                    </div>
                    
                    
                    <div>
                        <input type="checkbox" name="glossy" id="glossy" value="1"<?php
                        if ( $user->Gender == 'f' ) {
                            ?> checked="checked"<?php
                        }
                        ?> /><label for="glossy">Θέλω το μενταγιόν γυαλισμένο (κατάλληλο για κορίτσια)</label>
                    </div>
                    
                    <input type="submit" value="Αγορά" />
                </form>
            </div>
            <?php
        }
    }
?>
