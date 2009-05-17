<?php
    
    class ElementUserJoin extends Element {
        public function Render( tText $username, tText $id, tText $validtoken ) {
            global $page;
            global $rabbit_settings;
            global $user;
            global $libs;
            $libs->Load( 'contacts/contacts' );
            
            $page->SetTitle( 'Γίνε μέλος!' );
            if ( $user->Exists() ) {
                return Redirect( $rabbit_settings[ 'webadresss' ] );
            }
            $username = $username->Get();
            $id = $id->Get();
            $validtoken = $validtoken->Get();
            if( $id != "" ){
                $finder = new ContactFinder();
                $contact = $finder->FindById( $id );
                if( $contact->Validtoken == $validtoken && $contact->Invited == 1 ){
                    $email = $contact->Mail;
                    $parts = explode( '@', $contact->Mail );
                    $username = $parts[ 0 ];
                    $finder = new UserFinder();
                    if ( $finder->IsTaken( $username ) ){
                        $username = "";
                    }
                }
            }
            ?><div class="join">
                <div class="bubble">
                    <i class="tl"></i><i class="tr"></i>
                    <h2>Γίνε μέλος!</h2>
                    <form class="joinform">
                        <div>
                            <label for="join_name">Όνομα χρήστη:</label>
                            <input type="text" value="<?php
                            echo htmlspecialchars( $username );
                            ?>" id="join_name" />
                            <span><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />
                            <span>Πρέπει να δώσεις ένα όνομα χρήστη μεταξύ 4 και 20 χαρακτήρων!</span>
                            </span>
                            <span><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />
                            <span>Το όνομα που διάλεξες υπάρχει ήδη!</span>
                            </span>
                            <span><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />
                            <span>Το όνομα πρέπει να ξεκινάει από γράμμα και μπορεί να περιέχει μόνο γράμματα, αριθμούς, και τα σύμβολα - και _</span>
                            </span>
                            <p>Το όνομα με το οποίο θα εμφανίζεσαι, δεν μπορείς να το αλλάξεις αργότερα.</p>
                        </div>
                        <div>
                            <label for="join_pwd">Κωδικός πρόσβασης:</label>
                            <input type="password" value="" id="join_pwd" />
                            <span><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />
                            <span>Πρέπει να δώσεις έναν κωδικό πρόσβασης με τουλάχιστον 4 χαρακτήρες!</span>
                            </span>
                            <div>
                                <label for="join_repwd" style="padding-top: 5px">Πληκτρολόγησε τον ξανά:</label>
                                <input type="password" value="" id="join_repwd" />
                                <span><img src="<?php
                                echo $rabbit_settings[ 'imagesurl' ];
                                ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />
                                <span>Δεν έχεις πληκτρολογήσει σωστά τον κωδικό πρόσβασης!</span>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label for="join_email">E-mail:</label>
                            <input type="text" value="<?php
                            echo $email;
                            ?>" style="width:200px" id="join_email" />
                            <span><img src="<?php
                            echo $rabbit_settings[ 'imagesurl' ];
                            ?>exclamation.png" alt="Προσοχή" title="Προσοχή" />
                            <span>Το email που έχεις γράψει δεν είναι έγκυρο!</span>
                            </span>
                            <p>Η διεύθυνση e-mail που θα δηλώσεις πρέπει να είναι έγκυρη για να μπορέσεις να επιβεβαιώσεις τον λογαριασμό σου.</p>
                        </div>
                        <p>Η δημιουργία λογαριασμού συνεπάγεται την ανεπιφύλακτη αποδοχή των <a href="tos">όρων χρήσης</a>.</p>
                        <div style="text-align:center">
                            <a href="" class="button">Δημιουργία &raquo;</a>
                            <noscript>
                                <input type="submit" value="Δημιουργία &raquo;" />
                            </noscript>
                        </div>
                    </form>    
                    <i class="qleft"></i><i class="qright"></i>
                    <i class="qbottom"></i>
                    <i class="bl"></i><i class="br"></i>
                </div>
                <img src="images/button_ok_16.png" alt="Σωστή επαλήθευση" title="Σωστή επαλήθευση" style="display:none" />
            </div>
            <div id="join_tos">
                <div><?php
                Element( 'about/tos/text' );
                ?></div>
                <a href="" class="button" onclick="Modals.Destroy();return false">Κλείσιμο</a>
            </div><?php
            $page->AttachInlineScript( 'Join.JoinOnLoad();' );
            $finder = New UserFinder();
            if( $finder->IsTaken( $username ) && $username != '' ) {
                $page->AttachInlineScript( 'Join.UserExists();' );
            }
        }
    }
?>
