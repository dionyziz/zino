<?php
   class ElementFrontpageView extends Element {
        public function Render( tBoolean $newuser, tBoolean $validated ) {
            global $user;
            global $rabbit_settings;
            global $libs;
            global $page;
            
            $libs->Load( 'notify' );
            $newuser = $newuser->Get();
            $validated = $validated->Get();
            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $user, 0, 5 );
            $shownotifications = $notifs->TotalCount() > 0;
            $sequencefinder = New SequenceFinder();
            $sequences = $sequencefinder->FindFrontpage();
            ?><div class="frontpage"><?php
            if ( $validated && $user->Exists() && $user->Profile->Emailvalidated ) {
                ?><div class="ybubble"><div class="body">
                <a class="delete" href="" onclick="Frontpage.Closenewuser();return false"><img src="images/cancel.png" alt="Κλείσιμο" title="Κλείσιμο" /></a>
                <p>
                <strong>Η e-mail διεύθυνσή σου επιβεβαιώθηκε επιτυχώς.</strong>
                <br /><br />
                Για την δική σου ασφάλεια, μην δίνεις ποτέ τον κωδικό του λογαριασμού σου σε τρίτους. <br />
                Να θυμάσαι να κάνεις "Έξοδο" από το προφίλ σου κάθε φορά που χρησιμοποιείς δημόσιο υπολογιστή.
                <br /><br />
                <a href="" onclick="Frontpage.Closenewuser();return false">Εντάξει, θα το θυμάμαι!</a>
                </p>
                </div></div><?php
            }
            else if ( $newuser && $user->Exists() ) {
                $showschool = $user->Profile->Education >= 5 && $user->Profile->Placeid > 0;
                if ( !$shownotifications ) {
                    ?><div class="ybubble">
                        <div class="body">
                            <a class="delete" href="" onclick="Frontpage.Closenewuser();return false"><img src="images/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
                            <form>
                                <p style="margin:0">Αν είσαι φοιτητής επέλεξε τη σχολή σου αλλιώς το είδος της εκπαίδευσής σου:</p>
                                <div>
									<div id="selectplace">
										<span>Πόλη:</span><?php
										if ( $user->Profile->Placeid != 0 ) {
	                                        echo htmlspecialchars( $user->Profile->Location->Name );
	                                    }
	                                    else {
	                                        Element( 'user/settings/personal/place', $user );
	                                    }
                                    ?></div>
									<div id="selecteducation">
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
                if ( $notifs->TotalCount() > 10 ) {
                    $count = '10+';
                }
                else {
                    $count = $notifs->TotalCount();
                }
                
                $page->SetTitle( 'Zino (' . $count . ')' );
                $page->FinalizeTitle();
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
            
            Element( 'frontpage/image/list' , $sequences[ SEQUENCE_FRONTPAGEIMAGECOMMENTS ] );

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
                    <div id="shoutbox"><?php
                        Element( 'frontpage/shoutbox/list' , $sequences[ SEQUENCE_SHOUT ] );
                    ?></div>
                    <div class="onlinenow"><?php
                        Element( 'frontpage/online' );
                    ?></div>
                </div>
                <div class="right">
                    <div class="latest">
                        <h2>Πρόσφατα γεγονότα</h2>
                        <div class="comments"><?php
                            Element( 'frontpage/comment/list' , $sequences[ SEQUENCE_COMMENT ] );
                        ?></div>
                        <div class="eof"></div>
                        <div class="barfade">
                                <div class="leftbar"></div>
                                <div class="rightbar"></div>
                            </div>
                        <div class="journals"><?php
                            Element( 'frontpage/journal/list' , $sequences[ SEQUENCE_JOURNAL ] );
                        ?></div>
                        <div class="eof"></div>
                        <div class="barfade">
                                <div class="leftbar"></div>
                                <div class="rightbar"></div>
                            </div>
                        <div class="polls"><?php
                            Element( 'frontpage/poll/list' , $sequences[ SEQUENCE_POLL ] );
                        ?></div>
                    </div>
                
                </div>
            </div>
        </div>
        <div class="eof"></div><?php
        if ( $user->Exists() ) {
            switch ( strtolower( $user->Name ) ) {
                case 'dionyziz':
                case 'pagio91':
                case 'izual':
                case 'petrosagg18':
                case 'gatoni':
                case 'ted':
                case 'kostis90gr':
                case 'bowling':
                case 'parvati':
                case 'finlandos':
                case 'kardas_thrilikozzzz':
                case 'blink':
                case 'indy':
                case 'd3nnn1z':
                case 'chorvus':
                case 'peach':
                case 'kogi':
                case 'dimo0koc':
                case 'teddy':
                case 'agorf':
                case 'seraphim':
                case 'elsa':
                case 'funeral':
                case 'cmad':
                case 'teh-ninja':
                case 'intzakosd':
                case 'kolstad':
                case 'ronaldo7':
                case 'morvena':
                   Element( 'shoutbox/comet' );
                   Element( 'frontpage/image/comet' );
                   Element( 'frontpage/comment/comet' );
            }
            switch ( strtolower( $user->Name ) ) {
                case 'dionyziz':
                case 'pagio91':
                case 'izual':
                case 'petrosagg18':
                case 'gatoni':
                case 'ted':
                case 'kostis90gr':
		case 'pacman':
                    Element( 'frontpage/notification/comet' );
            }
        }
        //$page->AttachInlineScript( 'Frontpage.FrontpageOnLoad();' );
        }
    }
?>
