<?php
   class ElementFrontpageView extends Element {
        public function Render( tBoolean $newuser ) {
            global $user;
            global $rabbit_settings;
            global $libs;
            
            $libs->Load( 'notify' );
            $newuser = $newuser->Get(); // TODO
            $finder = New ImageFinder();
            $images = $finder->FindFrontpageLatest( 0, 15 );
            $finder = New NotificationFinder();
            $notifs = $finder->FindByUser( $user, 0, 5 );
            $shownotifications = count( $notifs ) > 0;
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
            if ( count( $images ) > 0 ) {
                ?><div class="latestimages">
                <ul><?php
                    foreach ( $images as $image ) {
                        ?><li><a href="?p=photo&amp;id=<?php
                        echo $image->Id;
                        ?>"><?php
                        Element( 'image/view' , $image->Id , $image->User->Id , $image->Width , $image->Height , IMAGE_CROPPED_100x100 , '' , $image->User->Name , '' , false , 0 , 0 );
                        ?></a></li><?php
                    }
                    ?>
                </ul>
                </div><?php
            }
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
                <div class="eof"></div>
                <div class="outshoutbox"><?php
                Element( 'frontpage/shoutbox/list' );
                ?></div><?php
            } 
            else {
                ?><div class="inuser">
					<div class="inshoutbox"><?php
                        Element( 'frontpage/shoutbox/list' );
                    ?></div>
					<div class="inlatestcomments"><?php
                       Element( 'frontpage/comment/list' );
                    ?></div><?php
					$finder = New UserFinder();
		            $users = $finder->FindOnline( 0, 50 );
		            if ( count( $users ) > 0 ) {        
		                ?><div class="inonlineusers">
		                    <h2<?php
		                        if ( count( $users ) > 1 ) {
		                            ?> title="<?php
		                            echo count( $users );
		                            ?> άτομα είναι online"<?php
		                        }
		                        ?>>Είναι online τώρα (<?php
		                        echo count( $users );
		                        ?>)</h2>
		                        <div class="list"><?php
		                            foreach( $users as $onuser ) {
		                                ?><a href="<?php
		                                Element( 'user/url', $onuser->Id , $onuser->Subdomain );
		                                ?>"><?php
                                        Element( 'user/avatar' , $onuser->Avatar->Id , $onuser->Id , $onuser->Avatar->Width , $onuser->Avatar->Height , $onuser->Name , 100 , '' , '' , false , 0 , 0 );
		                                ?></a><?php
		                            }    
		                        ?></div><?php
		                ?></div><?php
		            }
					?>
                </div>
				<div class="eof"></div>
                <div class="inlatestevents"><?php
                Element( 'event/list' );
                ?></div><?php
            }
            ?><div class="eof"></div>
        </div><?php
        }
    }
?>
