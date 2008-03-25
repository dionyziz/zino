<?php
	function ElementJournalView() {
		global $page;
		
		$page->AttachStyleSheet( 'css/journal/view.css' );
		
		Element( 'user/sections' , 'journal' );
		?><div id="journalview">
			<h2>The MacGyver sandwich</h2>
			<div class="journal" style="clear:none;">	
				<dl>
					<dd class="commentsnum">87 σχόλια</dd>
					<dd class="addfav"><a href="">Προσθήκη στα αγαπημένα</a></dd>
					<dd class="lastentries"><a href="">Παλαιότερες καταχωρήσεις&raquo;</a></dd>
				</dl>
				<div class="eof"></div>
				<p>
				Εξεταστική και το κάψιμο βαράει κόκκινο. Κάτι έπρεπε λοιπόν να κάνω για να σπάσω τη μονοτονία της καθημερινότητας<br />
				και του διαβάσματος. Σήμερα είπα να φτιάξω βραδυνό με έναν προτότυπο τρόπο. Έφτιαξα λοιπόν<br /> 
				ένα sandwich χωρίς να έχω ψηστιέρα. Όπως όμως λέει και η γνωστή παροιμία όποιος δεν έχει ψηστιέρα έχει σίδερο...<br /><br />
				Ξεκινάμε.<br />
				Ετοιμάζουμε το sandwich όπως θα κάναμε σε κάθε περίπτωση, απλά δε το γεμίζουμε με πράγματα που μπορεί να λιώσουν<br />
				πάρα πολύ εύκολα. Τυρί φυσικά και επιτρέπεται.<br />
				<img src="images/journal1.jpg" alt="Journal" /><br />
				Στη συνέχεια πέρνουμε αλουμινόχαρτο<br />
				<img src="images/journal2.jpg" alt="Journal" /><br />
				και τυλίγουμε καλά το sandwich έτσι ώστε να μην υπάρχουν πουθενά οπές και να μη μπορεί να ξεχυλίσει το λιωμένο τυρί.<br />
				Το αλουμίνόχαρτο θα πρέπει να έρχεται σε επαφή όμως με το τυρί για να λιώσει καλύτερα και γρηγορότερα.<br />
				<img src="images/journal3.jpg" alt="Journal" /><br />
				Αφού τυλίξαμε το sandwich σειρά έχει το σίδερο.<br />
				<img src="images/journal4.jpg" alt="Journal" /><br />
				Παίρνουμε το σίδερο και ανεβάζουμε τέρμα τη θερμοκρασία και περιμένουμε να ζεσταθεί.<br />
				<img src="images/journal5.jpg" alt="Journal" /><br />
				Μόλις γίνει αυτό ξέρουμε τι πρέπει να κάνουμε: δίνουμε στο sandwich και καταλαβαίνει. Πατάμε το sandwich με το σίδερο<br />
				για κάμποσο χρόνο και μετά το γυρνάμε και από την άλλη.	Προσοχή στην επιφάνεια πάνω στην οποία θα σιδερώσετε <br />
				το sandwich και στο αλουμινόχαρτο που καίει. Μετά από κάμποση ώρα πρέπει να είμαστε ok και θα έχει λιώσει και το τυρί.<br />
				Το sandwich είναι πλέον αρκετά πιο λεπτό<br />
				<img src="images/journal6.jpg" alt="Journal" /><br />
				Μπορούμε πλέον να το ξετυλίξουμε...<br />
				<img src="images/journal7.jpg" alt="Journal" /><br />
				και να το φάμε...<br />
				<img src="images/journal8.jpg" alt="Journal" /><br />
				καλή όρεξη...
				</p>
			</div>
			<div class="comments"><?php
				Element( 'comment/list' );
			?></div>
			<div class="eof"></div>		
		</div><?php
	}
?>