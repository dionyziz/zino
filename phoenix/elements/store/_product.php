<?php
    class ElementStoreProduct extends Element {
        public function Render( tString $name, tInteger $id ) {
            global $libs;
            global $page;
            global $user;
            
            $libs->Load( 'store' );
            $libs->Load( 'favourite' );
            $libs->Load( 'image/image' );
            $libs->Load( 'place' );
            $libs->Load( 'user/profile' );
            
            $id = $id->Get();
            $name = $name->Get();
            
            $page->AttachScript( 'js/store.js' );
            $page->AttachInlineScript( 'Store.OnLoad();' );
            
            $storefinder = New StoreItemFinder();
            
            if ( $name !== false ) {
                $item = $storefinder->FindByName( $name );
            }
            if ( $id !== false ) {
                $item = $storefinder->FindById( $id );
            }
            if ( $item === false ) {
                return Element( '404' );
            }
            
            $page->SetTitle( $item->Friendlyname );
            
            $purchasefinder = New StorePurchaseFinder();
            $purchases = $purchasefinder->FindByItemId( $item->Id );
            
            $favouritefinder = New FavouriteFinder();
            $loves = $favouritefinder->FindByEntity( $item );
            
            $igot = false;
            $ilove = false;
            if ( $user->Exists() ) {
                foreach ( $purchases as $purchase ) {
                    if ( $purchase->User->Id == $user->Id ) {
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
            
            Element( 'user/subdomainmatch' );
            
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
                    <img src="<?php
                    echo $item->Icon;
                    ?>" alt="
                    <?php
                    echo $item->Friendlyname;
                    ?>" />
                </div>
                <div class="productdetails">
                    <h2><?php
                    echo $item->Friendlyname;
                    ?> <span><img src="http://static.zino.gr/phoenix/store/<?php
                    echo $item->Price;
                    ?>euros.png" alt="<?php
                    echo $item->Price;
                    ?>€" /></span></h2>
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
                                ?>href="" onclick="$('#buynow').fadeIn();return false;"<?php
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
                <p class="remain"><?php
                    $remaining = $item->Remaining();
                    if ( $remaining ) {
                        ?>...απομέν<?php
                        if ( $remaining == 1 ) {
                            ?>ει 1 συλλεκτικό κομμάτι.<?php
                        }
                        else {
                            ?>ουν <?php
                            echo $remaining;
                            ?> συλλεκτικά κομμάτια.<?php
                        }
                    }
                    else {
                        ?>Και τα <?php
                        echo $item->Total;
                        ?> συλλεκτικά κομμάτια έχουν πουληθεί.<?php
                    }
                    ?>
                </p>
            </div>
            <div id="buynow" style="display:none">
                <form action="do/store/purchase" method="post">
                    <a class="close" onclick="$('#buynow').fadeOut();return false;" href="">X</a>
                    <h3>Η διεύθυνσή σου</h3>
                    <div>
                        <label for="placeid">Πόλη:</label>
                        <select name="placeid" id="placeid">
                        <option value="0"></option><?php
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
                        <span id="delivery1">Θα σου το αποστείλουμε ταχυδρομικώς μέσα στην επόμενη εβδομάδα. Όλα τα έξοδα μεταφοράς, αποστολής, και αντικαταβολής καλύπτονται δωρεάν από το Zino.</span>
                        
                        <span id="delivery2">Θα επικοινωνήσουμε μαζί σου τηλεφωνικά μέσα στις επόμενες 2 μέρες για παράδοση 
                        χέρι-με-χέρι από κάποιον αντιπρόσωπο του Zino στην πόλη σου.</span>
                    </div>
                    <div id="needaddy">
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
                    </div>
                    
                    <h3>Επικοινωνία</h3>
                    <div>
                        <label for="firstname">Όνομα:</label><input type="text" name="firstname" id="firstname" value="<?php
                        echo htmlspecialchars( $user->Profile->Firstname );
                        ?>" />
                    </div>
                    <div>
                        <label for="firstname">Επώνυμο:</label><input type="text" name="lastname" id="lastname" value="<?php
                        echo htmlspecialchars( $user->Profile->Lastname );
                        ?>" />
                    </div>
                    <div>
                        <label for="mobile">Το κινητό σου:</label><input type="text" name="mobile" id="mobile" value="<?php
                        echo htmlspecialchars( $user->Profile->Mobile );
                        ?>" />
                    </div>
                    
                    <h3><?php
                    echo $item->Friendlyname;
                    ?> - <?php
                    echo $item->Price;
                    ?>€</h3> <?php

                    
                    $prop_finder = new StorepropertyFinder();
                    $res = $prop_finder->FindByItemId( $item->Id );
                    $properties = array();
                    $prop_val = array();
                    foreach ( $res as $prop ) {
                        $properties[ $res->Type ] = true;
                        $prop_val[ $res->Type ][] = $res->Value;
                    }                     

                    foreach ( $properties as $key=>$val ) {
                        echo "<p>type - " . $key. " " . $val . "</p>";
                        foreach ( $prop_val[ $types ] as $key=>$val ) {
                                echo "<p>" . $key . " " . $val . "</p>";
                        }
                    }

                    
                    ?><div class="property">
                        <input type="checkbox" name="glossy" id="glossy" value="1"<?php
                        if ( $user->Gender == 'f' ) {
                            ?> checked="checked"<?php
                        }
                        ?> /><label for="glossy">Θέλω το μενταγιόν γυαλισμένο (glossy)</label>
                    </div>
                    
                    <input type="hidden" name="itemid" value="<?php
                    echo $item->Id;
                    ?>" />
                    <input type="submit" value="Αγορά τώρα" class="buy" />
                    
                    <div class="details">
                        Η πληρωμή θα γίνει κατά την παράδοση του προϊόντoς.
                    </div>
                </form>
            </div>
            <?php
        }
    }
?>
