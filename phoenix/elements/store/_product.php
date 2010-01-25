<?php
    class ElementStoreProduct extends Element {
        public function Render( tString $name, tInteger $id ) {
            global $libs;
            global $page;
            global $user;
            global $rabbit_settings;
            
            $libs->Load( 'store' );
            $libs->Load( 'favourite' );
            $libs->Load( 'image/image' );
            $libs->Load( 'place' );
            $libs->Load( 'user/profile' );

            Element( 'user/subdomainmatch' );

            $page->AttachScript( 'js/store.js' );
            
            $id = $id->Get();
            $name = $name->Get();          
            
            $storefinder = New StoreItemFinder();
            
            if ( $name !== "" ) {
                $item = $storefinder->FindByName( $name );
            }
            if ( $id !== 0 ) {
                $item = $storefinder->FindById( $id );
            }
            if ( $item === false ) {
                return Element( '404' );
            }

            
            $page->AttachInlineScript( 'Store.OnLoad(' . $item->Id . ');' );       

            $page->SetTitle( $item->Friendlyname ); 
        
            if( $item->Css != "" ) { 
		$page->AttachStylesheet( $item->Css );
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
                    <a href="http://www.zino.gr/store.php?p=product&amp;name=<?php echo $item->Name; ?>"><img src="http://static.zino.gr/phoenix/store/store.png" alt="STORE" /></a>
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
                    ?> <span><img src="<?php
                    if ( $item->Priceimage != '' ) {
                        echo $item->Priceimage;
                    }
                    else {
                        ?>http://static.zino.gr/phoenix/store/<?php
                        echo $item->Price;
                        ?>euros.png<?php
                    }
                    ?>" alt="<?php
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
                <?php
                    $shotfinder = New StoreActionshotFinder();
                    $shots = $shotfinder->FindByItemId( $item->Id );
                    
                    ?>Φωτογραφίες Μελών:
                    <ul class="lst ul2 border"><?php
                    foreach ( $shots as $shot ) {
                        $image = $shot->Image;
                            ?><li><a href="<?php
                            ob_start();
                            echo $rabbit_settings[ 'webaddress' ];
                            ?>/?p=photo&id=<?php
                            echo $image->Id;
                            echo htmlspecialchars( ob_get_clean() );
                            ?>"><?php
                            Element( 'image/view' , $image->Id , $image->Userid , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 , 0 );
                            ?></a></li>
                            <?php
                    }
                    ?></ul><?php
                ?>
                <h3 class="wantz">Το έχουν:</h3>
                <ul class="wantz"><?php
                    foreach ( $purchases as $purchase ) {
                        ?><li><a href="http://<?php
                            echo $purchase->User->Subdomain;
                            ?>.zino.gr/" title="<?php
                            echo $purchase->User->Name;
                            ?>">
                            <?php
                            Element( 'user/avatar', $purchase->User->Avatarid, $purchase->Userid, 100, 100,$purchase->User->Name, IMAGE_CROPPED_100x100, '', '', true, 100, 100);
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
            <h3 class="lurv">Το αγαπάνε:</h3>
                <ul class="lurv"><?php
                    foreach ( $loves as $love ) {
                        ?><li><a href="http://<?php
                            echo $love->User->Subdomain;
                            ?>.zino.gr/" title="<?php
                            echo $love->User->Name;
                            ?>">
                            <?php
                            Element( 'user/avatar', $love->User->Avatarid, $love->Userid, 100, 100,$love->User->Name, IMAGE_CROPPED_100x100, '', '', true, 50, 50 );
                        ?></a></li><?php
                    }
            ?></ul>
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
                        <span id="delivery1">Θα σου το αποστείλουμε ταχυδρομικώς.</span>
                        
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
                        $properties[ $prop->Type ] = true;
                        $prop_val[ $prop->Type ][] = $prop->Value;
                    }  
                    
                    ?><div class="property"><?php
                        if ( $properties[ "glossy" ] == true ) { //add property code manually
                                ?><input type="checkbox" name="glossy" id="glossy" value="1"<?php
                                if ( $user->Gender == 'f' ) {
                                    ?> checked="checked"<?php
                                }
                                ?> /><label for="glossy">Θέλω το μενταγιόν γυαλισμένο (glossy)</label><?php
                        }
                        if ( $properties[ "Size" ] == true ) { //add property code manually
                                ?><label for="size">Μέγεθος</label>        
                                  <select name="size"><?php
                                /*foreach ( $prop_val[ "Size" ] as $value ) {
                                        ?><option><?php
                                        echo $value;
                                        ?></option><?php
                                }*/
                                ?><option value="XS">XS</option>
                                  <option value="S">S</option>
                                  <option value="M" selected="selected">M</option>
                                  <option value="L">L</option>
                                  <option value="XL">XL</option>                    
                                  <option value="XXL">XXL</option><?php
                                ?></select><?php
                        } 
                    ?>
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
