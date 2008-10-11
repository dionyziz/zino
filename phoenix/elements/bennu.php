<?php

    class ElementBennu extends Element {
        public function Render( tBoolean $newuser ) {
        
            global $libs;
            global $user;	        
	        global $rabbit_settings;
            
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
                            <a href="" onclick="Frontpage.Closenewuser();return false"><img src="images/cancel.png" alt="Ακύρωση" title="Ακύρωση" /></a>
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
                    <div class="shoutbox"><?php
                        Element( 'frontpage/shoutbox/list' , $sequences[ SEQUENCE_SHOUT ] );
                    ?></div>
                    <div class="onlinenow"><?php
                        Element( 'frontpage/onlineforbennu' );
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
            
	        /*$libs->Load( 'user/user' );
	        $libs->Load( 'bennu/mybennu' );
	        
	        $userFinder = new UserFinder();
	        $input = array();
	        $input = $userFinder->FindOnline();
	        $input = $input[ 0 ];
	        $target = $userFinder->FindById( $user->Id );
	        
	        $res = Bennu_OnlineNow( $target, $input );

	        ?><h3>Results</h3><?php
	        foreach ( $res as $sample ) {
	            echo '<p>'.$sample->Name.'</p>';            
            }*/
        
/*
            global $user;
            global $libs;

            $prefage = $age->Get();
            $prefgender = strtolower( $gender->Get() );

            if ( $prefage < 1 || $prefage > 80 ) {
                $prefage = $user->Age();
            }

            if ( $prefgender != 'male' && $prefgender != 'female' && $prefgender != 'both' ) {
                $prefgender = ( $user->Gender() == 'male' ) ? 'female' : 'male';
            }

            $libs->Load( "bennu" );
            $bennu = New Bennu();
            
            $age = New BennuRuleAge();
            $age->Value = $prefage;
            $age->Score = 10;
            $age->Sigma = 2;
            
            $bennu->AddRule( $age );

            if ( $prefgender != 'both' ) {
                $sex = New BennuRuleSex();
                $sex->Value = $prefgender;
                $sex->Score = 5;
            
                $bennu->AddRule( $sex );
            }

            $bennu->Exclude( $user );
            $users = $bennu->Get( 20 );
            $scores = $bennu->Scores();
            $maxscore = $bennu->MaxScore();

            ?><h2>Friend Recommendations</h2>
            <h4>Powered by Bennu</h4>

            <form action="" method="get" style="background-color: #F8FBE2; padding: 5px">
                <input type="hidden" name="p" value="bennu" />
                Preferred Age: 
                <select name="age"><?php
                    for ( $i = 5; $i < 80; ++$i ) {
                        ?><option<?php
                        if ( $i == $prefage ) {
                            ?> selected="selected"<?php
                        }
                        ?>><?php
                        echo $i;
                        ?></option><?php
                    }
                ?></select><br />
                Preferred Gender:
                <select name="gender"><?php
                    $genders = array( 'male', 'female', 'both' );

                    for ( $i = 0; $i < count( $genders ); ++$i ) {
                        ?><option<?php
                            if ( $prefgender == $genders[ $i ] ) {
                                ?> selected="selected"<?php
                            } 
                            ?>><?php 
                            echo $genders[ $i ]; 
                        ?></option><?php
                    }

                ?></select><br />
                <input type="submit" value="submit" /><br />
            </form>

            <ul style="list-style-type: none"><?php

            // die( print_r( $scores ) . "<br /><br />" . print_r( $users ) );

            for ( $i = 0; $i < count( $users ); ++$i ) {
                $buser = $users[ $i ];
                ?><li><?php
                    Element( "user/static", $buser );
                ?> - <?php
                    echo ( $scores[ $i ] * 100 ) / $maxscore;
                ?>%</li><?php
            }

            ?></ul><?php
*/            
        }
    }
?>
