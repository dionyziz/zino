<?php
	class ElementContactsPage extends Element {
        public function Render( tInteger $minage, tInteger $maxage,
            tInteger $placeid, tText $gender,
            tText $orientation, tText $name,
            tInteger $limit, tInteger $pageno
        ) {
            global $user;
            global $page;
            global $rabbit_settings;
            if ( !$user->Exists() ) {
                return Redirect( $rabbit_settings[ 'webaddress' ] );
            }
            $page->AttachInlineScript( 'contacts.init();' );
            $page->AttachInlineScript( 'contacts.frontpage = "' . $rabbit_settings[ 'webaddress' ] . '";' );
            $page->SetTitle( "Αναζήτηση φίλων" );
            
            $minage = $minage->Get();
            $maxage = $maxage->Get();
            $placeid = $placeid->Get();
            $gender = $gender->Get();
            $orientation = $orientation->Get();
            $name = $name->Get();
            $pageno = $pageno->Get();
            $limit = 24;
            if ( $pageno <= 0 ) {
                $pageno = 1;
            }
			?>
            <div class="invite_contacts">
                <ul id="top_tabs">
                    <li id='searchInZino' class="selected">Αναζήτηση στο Zino</li>
                    <li id='otherNetworks'>Αναζήτηση σε άλλα δίκτυα</li>
                    <li id='ByEmail'>Πρόσκληση με e-mail</li>
                </ul>
                <div id="body">
                    <div class="tab" id="searchtab">
                        <?php
                            Element( 'search/view',
                                $minage, $maxage, $placeid, $gender, $orientation, $name,
                                $limit, $pageno
                            );
                        ?>
                    </div>
                    <div class="tab" id="login">
                        <ul id="left_tabs">
                            <li class="selected"><span id="hotmail">MSN</span></li>
                            <?php/*<li><span id="hi5">Hi5</span></li> */?>
                            <li><span id="yahoo">Yahoo</span></li>
                            <li><span id="gmail">Gmail</span></li>
                        </ul>
                        <div class="inputs" id="mail">
                            <div><label>Το username σου</label></div>
                            <input class="text" type="text"></input>
                        </div>
                        <div class="inputs" id="password">
                            <div><label>Κωδικός</label></div>
                            <input class="text" type="password"></input>
                        </div>
                        <p id="security">
                            Δεν θα αποθηκεύσουμε τον κωδικό και δεν θα στείλουμε προσκλήσεις χωρίς να σε ρωτήσουμε.
                        </p>
                    </div>
                    <center class="tab" id="loading">
                        <h1>Φορτώνουμε τις επαφές σου</h1>
                        <img src="http://static.zino.gr/phoenix/contacts/loader.gif" />
                        <h2>Αυτό μπορεί να πάρει δύο λεπτά...</h2>
                    </center>
                    <center class="tab" id="notAny">
                        <h1></h1>
                    </center>
                    <div class="tab networks" id="contactsInZino">
                        <h3></h3>
                        <div class="contacts">
                        </div>
                        <div class="selectAll">
                            <span class="all">Επιλογή όλων</span> 
                            <span class="none">Αποεπιλογή όλων</span>
                        </div>
                    </div>
                    <div class="tab networks" id="contactsNotZino">
                        <h3></h3>
                        <div class="contacts">
                        </div>
                        <div class="selectAll">
                            <span class="all">Επιλογή όλων</span> 
                            <span class="none">Αποεπιλογή όλων</span>
                        </div>
                    </div>
                    
                    <div class="tab" id="inviteByEmail">
                        <div id="contactMail">
                            <div><label>Γράψε τα email των φίλων σου</label></div>
                            <textarea class="text"></textarea>
                            <div class="example">Παράδειγμα: someone@gmail.com someoneelse@hotmail.com κλπ.</div>
                        </div>
                    </div>
                    
                    <div id="foot">
                        <input onfocus="this.blur()" type="submit" value=""></input>
                    </div>
                </div>
                <div class="eof"></div>
            </div>
            
		<?php
		}
	}
?>
