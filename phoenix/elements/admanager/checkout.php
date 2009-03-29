<?php
    class ElementAdManagerCheckout extends Element {
        function Render( tInteger $id ) {
            global $user;
            global $libs;
            
            $libs->Load( 'admanager' );
            
            $id = $id->Get();
            
            if ( !$user->HasPermission( PERMISSION_AD_EDIT ) ) {
                ?>Δεν μπορείτε να επεξεργαστείτε διαφημίσεις.<?php
                return;
            }
            
            $ad = New Ad( $id );
            if ( !$ad->Exists() ) {
                ?>Η διαφήμιση αυτή δεν υπάρχει.<?php
                return;
            }
            
            if ( $ad->Userid != $user->Id ) {
                ?>Η διαφήμιση αυτή δε φαίνεται να σας ανήκει.<?php
                return;
            }
            
            ?><div class="buyad">
            <h2 class="ad">Διαφήμιση στο Zino</h2>
            <div class="create checkout">
                <div class="left" style="width:400px;padding-left:50px">
                    <div class="input">
                        <label>Πόσες προβολές θα θέλατε να αγοράσετε;</label>
                        <table class="adpackages">
                            <thead>
                                <tr>
                                    <td>Αριθμός προβολών</td>
                                    <td>Κόστος ανά 1000 προβολές</td>
                                    <td>Κόστος</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <input type="radio" name="numviews" class="radio" id="numviews1" />
                                        <label for="numviews1">1,000</label>
                                    </td>
                                    <td>25€</td>
                                    <td><strong>25€</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="radio" name="numviews" class="radio" id="numviews5" />
                                        <label for="numviews5">5,000</label>
                                    </td>
                                    <td>22€</td>
                                    <td><strong>110€</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="radio" name="numviews" class="radio" id="numviews10" />
                                        <label for="numviews10">10,000</label>
                                    </td>
                                    <td>20€</td>
                                    <td><strong>200€</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="radio" name="numviews" class="radio" id="numviews50" />
                                        <label for="numviews50">50,000</label>
                                    </td>
                                    <td>18€</td>
                                    <td><strong>900€</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="radio" name="numviews" class="radio" id="numviews100" />
                                        <label for="numviews100">100,000</label>
                                    </td>
                                    <td>17€</td>
                                    <td><strong>1,700€</strong></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="radio" name="numviews" class="radio" id="numviews500" />
                                        <label for="numviews500">500,000</label>
                                    </td>
                                    <td>15€</td>
                                    <td><strong>7,500€</strong></td>
                                </tr>
                            </tbody>
                        </table>
                        <p class="more">Αν ενδιαφέρεστε για συμφωνίες μεγαλύτερες των 500,000 προβολών, επικοινωνήστε
                        μαζί μας στο <a href="mailto:ads@zino.gr">ads@zino.gr</a> για να συζητήσουμε
                        τις λεπτομέρειες τις καμπάνιας σας.</p>
                    </div>
                    
                    <div class="input">
                        <label>Όνομα και επώνυμο:</label>
                        <input type="text" name="fullname" value="" />
                    </div>
                    
                    <div class="input">
                        <label>Διεύθυνση e-mail:</label>
                        <input type="text" name="email" value="<?php
                        echo htmlspecialchars( $user->Profile->Email );
                        ?>" />
                    </div>

                    <div class="input">
                        <label>Τρόπος πληρωμής:</label>
                        <ul>
                            <li>
                                <input type="radio" name="payment" class="radio" id="paycredit" value="credit" /> 
                                <label for="paycredit">Με πιστωτική κάρτα</label>
                                <ul class="credit">
                                    <li>
                                        <img src="http://static.zino.gr/phoenix/logo_visa.gif" alt="VISA" title="VISA" />
                                    </li><li>
                                        <img src="http://static.zino.gr/phoenix/logo_mastercard.gif" alt="MasterCard" title="MasterCard" />
                                    </li><li>
                                        <img src="http://static.zino.gr/phoenix/logo_americanexpress.gif" alt="American Express" title="American Express" />
                                    </li><li>
                                        <img src="http://static.zino.gr/phoenix/logo_discover.gif" alt="Discover" title="Discover" />
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" name="payment" class="radio" id="paypaypal" value="paypal" /> 
                                <label for="paypaypal">Μέσω PayPal</label>
                                <ul class="credit">
                                    <li>
                                        <img src="https://www.paypal.com/en_GB/GB/i/logo/PayPal_mark_37x23.gif" alt="PayPal" title="PayPal" />
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <input type="radio" name="payment" class="radio" id="paybank" value="bank" />
                                <label for="paybank">Με κατάθεση σε τραπεζικό λογαριασμό</label>
                            </li>
                        </ul>
                    </div>
                    
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="paypal">
                        <input type="hidden" name="cmd" value="_s-xclick" />
                        <input type="hidden" name="hosted_button_id" value="4309716" />
                        <input type="image" src="http://static.zino.gr/phoenix/buy.gif" name="submit" alt="PayPal - The safer, easier way to pay online." class="paypal" style="width: 121px; height: 26px;" />
                        <img alt="" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                    </form>
                    <p class="confirm">Θα έχετε την δυνατότητα να επιβεβαιώσετε την επιλογή σας πριν πληρώσετε.</p>
                </div>
                <div class="right">
                    <div class="ads"><?php
                        Element( 'admanager/view', $ad, false );
                    ?></div>
                    <p>
                    Οι online πληρωμές σας γίνονται με ασφάλεια με την χρήση PayPal.
                    <a href="https://www.paypal.com/helpcenter/main.jsp?cmd=_help&amp;t=solutionTab&amp;solutionId=12261" target="_blank">Μάθετε περισσότερα</a>
                    </p>
                </div>
                <div class="eof"></div>
            </div>
            </div><?php
        }
    }
?>
