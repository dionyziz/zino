<?php
   class ElementFrontpageView extends Element {
        public function Render( tBoolean $newuser ) {
            global $user;
            global $rabbit_settings;
            global $libs;
            
            $libs->Load( 'notify' );
            $newuser = $newuser->Get(); // TODO
            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $user, 0, 5 );
            $shownotifications = count( $notifs ) > 0;
            $sequencefinder = New SequenceFinder();
            $sequences = $sequencefinder->FindFrontpage();
            ?><div class="frontpage"><?php
            if ( $newuser && $user->Exists() ) {
                $showschool = $user->Profile->Education >= 5 && $user->Profile->Placeid > 0;
                if ( !$shownotifications ) {
                    ?><div class="ybubble">
                        <div class="body">
                            <a href="" onclick="Frontpage.Closenewuser();return false;"><img src="images/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
                            <form>
                                <p style="margin:0">Αν είσαι φοιτητής επέλεξε τη σχολή σου αλλιώς το είδος της εκπαίδευσής σου:</p>
                                <div>
                                    <span>Πόλη:</span><?php
                                    if ( $user->Profile->Placeid != 0 ) {
                                        echo htmlspecialchars( $user->Profile->Location->Name );
                                    }
                                    else { 
                                        ?><div id="selectplace"><?php
                                        Element( 'user/settings/personal/place', $user );
                                        ?></div><?php
                                    }
                                    ?><div id="selecteducation">
                                        <span>Εκπαίδευση:</span><?php
                                        Element( 'user/settings/personal/education', $user );
                                    ?></div>
                                    <div id="selectuni"<?php
                                    if ( !$showschool ) {
                                        ?> class="invisible"<?php
                                    }
                                    ?>>
                                    <span>Πανεπιστήμιο:</span><?php
                                        if ( $showschool ) {
                                            Element( 'user/settings/personal/school', $user->Profile->Placeid, $user->Profile->Education );
                                        }
                                    ?></div>
                                    <div class="saving invisible">
                                        <img src="<?php
                                        echo $rabbit_settings[ 'imagesurl' ];
                                        ?>ajax-loader.gif" alt="Γίνεται αποθήκευση" title="Γίνεται αποθήκευση" /> Γίνεται αποθήκευση
                                    </div>
                                    <div class="saved invisible">
                                        Έγινε αποθήκευση
                                    </div>
                                </div>
                                <p>Μπορείς να το κάνεις και αργότερα από τις ρυθμίσεις.</p>
                            </form>
                        </div>
                        <i class="bl"></i>
                        <i class="br"></i>
                    </div><?php
                }
            }
            if ( $shownotifications ) {
                ?><div class="notifications">
                    <h3>Ενημερώσεις</h3>
                    <div class="list"><?php
                        Element( 'notification/list', $notifs );
                    ?></div>
                    <div class="expand">
                        <a href="" title="Απόκρυψη">&nbsp;</a>
                    </div>
                </div><?php
            }
            ?><div class="zinomeeting">
                <h3>Διαγωνισμός Avatars</h3>
                Την <b>Δευτέρα 20 Οκτωβρίου</b> λήγει η προθεσμία αποστολής δηλώσεων συμμετοχής για τον μεγάλο διαγωνισμό σχεδιασμού Avatar στο Ζίνο.
                Για περισσότερες πληροφορίες σχετικά με τον διαγωνισμό, κάντε click <b><a href="http://www.zino.gr/?p=journal&id=5231">Εδώ</a></b>.
           </div><?php
            Element( 'frontpage/image/list' , $sequences[ TYPE_IMAGE ] );
            if ( !$user->Exists() ) {
                ?><div class="members">
                    <div class="join">
                        <form action="" method="get">
                            <h2>Δημιούργησε το προφίλ σου!</h2>
                            <div>
                                <input type="hidden" name="p" value="join" />
                                <label>Όνομα:</label><input type="text" name="username" />
                            </div>
                            <div>
                                <input value="Δημιουργία &raquo;" type="submit" /> 
                            </div>
                        </form>
                    </div>
                    <div class="login">
                        <form action="do/user/login" method="post">
                            <h2>Είσοδος στο zino</h2>
                            <div>
                                <label>Όνομα:</label> <input type="text" name="username" />
                            </div>
                            <div>
                                <label>Κωδικός:</label> <input type="password" name="password" />
                            </div>
                            <div>
                                <input type="submit" value="Είσοδος &raquo;" />
                            </div>
                        </form>
                    </div>
                </div>
                <div class="eof"></div><?php
            } 
            ?><div class="inuser">
                <div class="left">
                    <div class="shoutbox"><?php
                        Element( 'frontpage/shoutbox/list' , $sequences[ TYPE_SHOUT ] );
                    ?></div>
                    <div class="onlinenow"><?php
                        Element( 'frontpage/online' );
                    ?></div>
                </div>
                <div class="right">
                    <div class="latest">
                        <h2>Πρόσφατα γεγονότα</h2>
                        <div class="comments"><?php
                            Element( 'frontpage/comment/list' , $sequences[ TYPE_COMMENT ] );
                        ?></div>
                        <div class="eof"></div>
                        <div class="barfade">
                                <div class="leftbar"></div>
                                <div class="rightbar"></div>
                            </div>
                        <div class="journals"><?php
                            Element( 'frontpage/journal/list' , $sequences[ TYPE_JOURNAL ] );
                        ?></div>
                        <div class="eof"></div>
                        <div class="barfade">
                                <div class="leftbar"></div>
                                <div class="rightbar"></div>
                            </div>
                        <div class="polls"><?php
                            Element( 'frontpage/poll/list' , $sequences[ TYPE_POLL ] );
                        ?></div>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="eof"></div><?php
        }
    }
?>
