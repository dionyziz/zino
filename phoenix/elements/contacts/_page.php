<?php
	class ElementContactsPage extends Element {
		public function Render(){
            global $user;
            if ( !$user->Exists() ) {
                return Redirect( $rabbit_settings[ 'webaddress' ] );
            }
			?>
            <div class="invite_contacts">
                <ul id="top_tabs">
                    <li>Αναζήτηση στο Zino</li>
                    <li id='otherNetworks' class="selected">Αναζήτηση σε άλλα δίκτυα</li>
                    <li id='ByEmail'>Πρόσκληση με e-mail</li>
                </ul>
                <ul id="left_tabs">
                    <li class="selected"><span id="hotmail">MSN</span></li>
                    <?php/*<li><span id="hi5">Hi5</span></li> */?>
                    <li><span id="yahoo">Yahoo</span></li>
                    <li><span id="gmail">Gmail</span></li>
                </ul>
                <div id="body">
                    <div id="login">
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
                    <div id="inviteByEmail">
                        <div class="inputs" id="contactMail">
                            <div><label>Γράψε τα email των φίλων σου</label></div>
                            <textarea class="text"></textarea>
                            <div>Παράδειγμα: someone@gmail.com,someoneelse@hotmail.com κλπ.</div>
                        </div>
                    </div>
                    <center id="loading">
                        <h1>Φορτώνουμε τις επαφές σου</h1>
                        <img src="http://static.zino.gr/phoenix/contacts/loader.gif" />
                        <h2>Αυτό μπορεί να πάρει δύο λεπτά...</h2>
                    </center>
                    <div class="step" id="contactsInZino">
                        <h3></h3>
                        <div class="contacts">
                        </div>
                        <div class="selectAll">
                            <span class="all">Επιλογή όλων</span> 
                            <span class="none">Αποεπιλογή όλων</span>
                        </div>
                    </div>
                    <div class="step" id="contactsNotZino">
                        <h3></h3>
                        <div class="contacts">
                        </div>
                        <div class="selectAll">
                            <span class="all">Επιλογή όλων</span> 
                            <span class="none">Αποεπιλογή όλων</span>
                        </div>
                    </div>
                    <div id="foot">
                        <input onfocus="this.blur()" type="submit" value=""></input>
                    </div>
                </div>
            </div>
            <div class="eof"></div>
		<?php
		}
	}
?>
