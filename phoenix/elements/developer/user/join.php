<?php
    
    class ElementDeveloperUserJoin extends Element {
        public function Render( tText $username, tText $id, tText $validtoken ) {
            global $page;
            global $rabbit_settings;
            global $user;
            global $libs;

            $libs->Load( 'contacts/contacts' );
            $libs->Load( 'rabbit/helpers/http' );
            
            $page->SetTitle( 'Γίνε μέλος!' );
            if ( $user->Exists() ) {
                return Redirect( $rabbit_settings[ 'webadresss' ] );
            }
            $username = $username->Get();
            $id = $id->Get();
            $validtoken = $validtoken->Get();
            if( $id != "" ){
                $finder = New ContactFinder();
                $contact = $finder->FindById( $id );
                if( $contact->Validtoken == $validtoken && $contact->Invited == 1 ){
                    $email = $contact->Mail;
                    $mailfinder = New UserProfileFinder();
                    $userprofiles = $mailfinder->FindAllUsersByEmails( array( $email ) );
                    if ( count( $userprofiles ) != 0 ){
                        $email = '';
                    }
                    $parts = explode( '@', $contact->Mail );
                    $username = $parts[ 0 ];
                    $finder = New UserFinder();
                    $_SESSION[ 'contact_id' ] = $id;
                    if ( $finder->IsTaken( $username ) ){
                        $username = "";
                    }
                }
            }
            ?><div class="join">
                <div class="bubble">
                    <i class="qtop"></i>
                    <i class="s2_0010 tl"></i><i class="s2_0011 tr"></i>
                    <h2>Γίνε μέλος!</h2>
                    <form class="joinform">
                        <div>
                            <label for="join_name">Όνομα χρήστη:</label>
                            <input type="text" value="<?php
                            echo htmlspecialchars( $username );
                            ?>" id="join_name" />
                            <span>
                                <span class="s1_0034">&nbsp;</span>
                                <span>Πρέπει να δώσεις ένα όνομα χρήστη μεταξύ 4 και 20 χαρακτήρων!</span>
                            </span>
                            <span>
                                <span class="s1_0034">&nbsp;</span>
                                <span>Το όνομα που διάλεξες υπάρχει ήδη!</span>
                            </span>
                            <span>
                                <span class="s1_0034">&nbsp;</span>
                                <span>Το όνομα πρέπει να ξεκινάει από γράμμα και μπορεί να περιέχει μόνο γράμματα, αριθμούς, και τα σύμβολα - και _</span>
                            </span>
                            <p>Το όνομα με το οποίο θα εμφανίζεσαι, δεν μπορείς να το αλλάξεις αργότερα.</p>
                        </div>
                        <div>
                            <label for="join_pwd">Κωδικός πρόσβασης:</label>
                            <input type="password" value="" id="join_pwd" />
                            <span>
                                <span class="s1_0034">&nbsp;</span>
                                <span>Πρέπει να δώσεις έναν κωδικό πρόσβασης με τουλάχιστον 4 χαρακτήρες!</span>
                            </span>
                            <div>
                                <label for="join_repwd" style="padding-top: 5px">Πληκτρολόγησε τον ξανά:</label>
                                <input type="password" value="" id="join_repwd" />
                                <span>
                                    <span class="s1_0034">&nbsp;</span>
                                    <span>Δεν έχεις πληκτρολογήσει σωστά τον κωδικό πρόσβασης!</span>
                                </span>
                            </div>
                        </div>
                        <div>
                            <label for="join_email">E-mail:</label>
                            <input type="text" value="<?php
                            echo $email;
                            ?>" style="width:200px" id="join_email" />
                            <span>
                                <span class="s1_0034">&nbsp;</span>
                                <span>Το email που έχεις γράψει δεν είναι έγκυρο!</span>
                            </span>
                            <span>
                                <span class="s1_0034">&nbsp;</span>
                                <span>Το e-mail που διάλεξες υπάρχει ήδη!</span>
                            </span>
                            <p>Η διεύθυνση e-mail που θα δηλώσεις πρέπει να είναι έγκυρη για να μπορέσεις να επιβεβαιώσεις τον λογαριασμό σου.</p>
                        </div>
                        <p>Η δημιουργία λογαριασμού συνεπάγεται την ανεπιφύλακτη αποδοχή των <a href="legal">όρων χρήσης</a>.</p>
                        <div style="text-align:center">
                            <a href="" class="button">Δημιουργία &raquo;</a>
                            <noscript>
                                <input type="submit" value="Δημιουργία &raquo;" />
                            </noscript>
                        </div>
                    </form>    
                    <i class="qleft"></i><i class="qright"></i>
                    <i class="qbottom"></i>
                    <i class="s2_0012 bl"></i><i class="s2_0013 br"></i>
                </div>
                <img src="images/button_ok_16.png" alt="Σωστή επαλήθευση" title="Σωστή επαλήθευση" style="display:none" />
            </div>
            <div id="join_tos">
                <div><?php
                Element( 'developer/about/legal/tos' );
                ?></div>
                <a href="" class="button" onclick="">Κλείσιμο</a>
            </div><?php
            $page->AttachInlineScript( 'Join.JoinOnLoad();' );
            $finder = New UserFinder();
            if( $finder->IsTaken( $username ) && $username != '' ) {
                $page->AttachInlineScript( 'Join.UserExists();' );
            }
        }
    }
?>
