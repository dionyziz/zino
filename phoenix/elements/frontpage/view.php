<?php
    class ElementFrontpageView extends Element {
        public function Render( tBoolean $newuser, tBoolean $validated ) {
            global $user;
            global $rabbit_settings;
            global $libs;
            global $page;
            
            $libs->Load( 'notify/notify' );
            $newuser = $newuser->Get();
            $validated = $validated->Get();
            if ( $user->Exists() ) {
                $finder = New NotificationFinder();
                $notifs = $finder->FindByUser( $user, 0, 8 );
                $shownotifications = $notifs->TotalCount() > 0;
            }
            else {
                $shownotifications = false;
            }
            $sequencefinder = New SequenceFinder();
            $sequences = $sequencefinder->FindFrontpage();
            ?><div id="frontpage"><?php
            if ( $validated && $user->Exists() && $user->Profile->Emailvalidated ) {
                ?><div class="ybubble"><div class="body">
                <a class="delete" href="" onclick="Frontpage.Closenewuser();return false"><img src="http://static.zino.gr/images/icons/cancel.png" alt="Κλείσιμο" title="Κλείσιμο" /></a>

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
                $libs->Load( 'user/profile' );
                $showschool = $user->Profile->Education >= 5 && $user->Profile->Placeid > 0;
                if ( !$shownotifications ) {
                    ?><div class="ybubble">
                        <div class="body">
                            <a class="delete" href="" onclick="Frontpage.Closenewuser();return false">&nbsp;</a>
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
                /*if ( $notifs->TotalCount() > 10 ) {
                    $count = '10+';
                }
                else {
                    $count = $notifs->TotalCount();
                }
                $page->SetTitle( 'Zino (' . $count . ')' );
                $page->FinalizeTitle();
                */
                if ( count( $notifs ) > 5 ) {
                    $notifs = $notifs->ToArray();
                    $vnotifs = array_slice( $notifs , 0 , 5 );
                    $inotifs = array_slice( $notifs , 5 );
                    $page->AttachInlineScript( "Notification.VNotifs = 5;Notification.INotifs = " . count( $inotifs ) .";" );
                }
                else {
                    $vnotifs = $notifs;
                    $page->AttachInlineScript( "Notification.VNotifs = " . count( $notifs ) . ";Notification.INotifs = 0;" );
                }
                ?><div id="notifications">
                    <h3>Ενημερώσεις</h3>
                    <div id="notiflist"><?php
                        Element( 'notification/list', $vnotifs );
                    ?></div>
                    <div id="inotifs" class="invisible"><?php
                        if ( isset( $inotifs ) ) {
                            Element( 'notification/list' , $inotifs );
                        }
                    ?></div>
                    <div class="expand">
                        <a id="notifexpand" href="" title="Απόκρυψη" class="s1_0024" >&nbsp;</a>
                    </div>
                </div><?php
            }
            else {
                $page->AttachInlineScript( "Notification.VNotifs = 0;Notification.INotifs= 0;" );
            }
            
            ?><a href="http://atdhe.net/watch-bbc-news.php" style="border:1px solid #aaa;background-color: #fffbcd; display: block; border-radius:5px; -moz-border-radius: 5px; -webkit-border-radius: 5px; margin: 30px auto 30px auto; padding: 15px; text-align: center; font-weight: bold">3 νεκροί στα επεισόδια στην Αθήνα αυτή τη στιγμή</a><?php
            
            Element( 'frontpage/image/list' , $sequences[ SEQUENCE_FRONTPAGEIMAGECOMMENTS ] );

            /* commented out by ted
            if ( !$user->Exists() ) {
                ?><div class="frontpagejoin">
                    <div class="planet">
                        <form action="" method="get">
                            <div>
                                <input type="hidden" name="p" value="join" />
                                <input id="newusername" type="text" name="username" value="ψευδώνυμο" />
                                <input id="snewusername" type="submit" value="&nbsp;" />
                            </div>
                        </form>
                    </div>
                </div><?php
            } */
            ?><div id="fp_inuser">
                <div id="fp_left">
                    <div id="shoutbox"><?php
                        Element( 'frontpage/shoutbox/list' , $sequences[ SEQUENCE_SHOUT ] );
                    ?></div><?php
                    if ( $user->Exists() ) {
                        ?><div class="onlinenow"><?php
                            Element( 'frontpage/online' );
                        ?></div><?php
                    }
                ?></div>
                <div id="fp_right">
                    <div id="fp_latest">
                        <h2>Πρόσφατα γεγονότα</h2>
                        <div class="comments"><?php
                            Element( 'frontpage/comment/list' , $sequences[ SEQUENCE_COMMENT ] );
                        ?></div>
                        <div class="eof"></div>
                        <div class="commfading"></div>
                        <div class="barfade">
                                <div class="leftbar"></div>
                                <div class="rightbar"></div>
                            </div>
                        <div class="journals"><?php
                            Element( 'frontpage/journal/list', $sequences[ SEQUENCE_JOURNAL ] );
                        ?></div>
                        <div class="eof"></div>
                        <div class="barfade">
                                <div class="leftbar"></div>
                                <div class="rightbar"></div>
                            </div>
                        <div class="polls"><?php
                            Element( 'frontpage/poll/list', 0 );
                        ?></div>
                    </div>
                </div>
            </div>
            </div>
            <div class="eof"></div><?php
            Element( 'shoutbox/comet' );
            Element( 'frontpage/comment/comet' );
            Element( 'frontpage/image/comet' );
            if ( $user->Exists() ) {
                Element( 'frontpage/notification/comet' );
            }
            $page->AttachInlineScript( 'Frontpage.FrontpageOnLoad();' );
        }
    }
?>
