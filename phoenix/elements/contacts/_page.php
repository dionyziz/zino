<?php
	class ElementContactsPage extends Element {
		public function Render(){
			?>
			<div class="content" id="content">
				<div class="invite_contacts">
					<ul id="top_tabs">
						<li>Αναζήτηση στο Zino</li>
						<li class="selected">Αναζήτηση σε άλλα δίκτυα</li>
						<li>Πρόσκληση με e-mail</li>
					</ul>
					<div class="eof"></div>
					<ul id="left_tabs">
						<li><span id="msn">MSN</span></li>
					<!--    <li><span id="hi5">Hi5</span></li> -->
						<li><span id="yahoo">Yahoo</span></li>
						<li class="selected"><span id="gmail">Gmail</span></li>
					</ul>
					<div id="body">
						<div id="login">
							<div class="inputs" id="mail">
								<div><label>Το e-mail σου</label></div>
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
						<center id="loading">
							<h1>Φορτώνουμε τις επαφές σου...</h1>
							<img src="http://static.zino.gr/phoenix/ajax-loader.gif" />
							<h2>Αυτό μπορεί να πάρει δύο λεπτά</h2>
							<span onclick="contacts.backToLogin()">back </span>
							<span onclick="contacts.previwContactsInZino()">next</span>
						</center>
						<div class="step" id="contactsInZino">
							<h3>7 επαφές σου έχουν Zino! Πρόσθεσέ τις στους φίλους σου...</h3>
							<div class="contacts">
								<div class="contact">
									<input type="checkbox"></input>
									<img src="http://images2.zino.gr/media/3890/140401/140401_100.jpg" />
									<div class="contactUsername">Ted</div>
									<div class="contactMail">thsourg@gmail.com</div>
								</div>
							</div>
							<div class="selectAll">
								<span class="all">Επιλογή όλων</span> 
								<span class="none">Αποεπιλογή όλων</span>
							</div>
						</div>
						<div class="step" id="contactsNotZino">
							<h3>7 επαφές σου δεν έχουν Zino ακόμα! Προσκάλεσέ τους τώρα!</h3>
							<div class="contacts">
								<div class="contact">
									<input type="checkbox"></input>
									<div class="contactMail">thsourg@gmail.com</div>
								</div>
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
			</div>
		<?php
		}
	}
?>
