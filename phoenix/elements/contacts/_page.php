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
						<li><span id="hotmail">MSN</span></li>
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
							<h1>Φορτώνουμε τις επαφές σου</h1>
							<img src="http://www.ajaxload.info/cache/FF/FF/FF/00/00/00/5-0.gif" />
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
			</div>
		<?php
		}
	}
?>
