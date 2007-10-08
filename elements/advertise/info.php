<?php
	function ElementAdvertiseInfo() {
		global $page;
		
		$page->SetTitle( 'Διαφήμιση στο chit-chat' );
		$page->AttachStyleSheet( 'css/advertise.css' );
		$page->AttachScript( 'js/advertise.js' );
		?><br /><br /><div class="body">
			<h2>Διαφήμιση στο chit-chat</h2>
			
			<span class="question">Τι είναι το chit-chat;</span><br />
			<span class="answer">
			Το chit-chat είναι ένα ταχύτατα αναπτυσσόμενο community νέων ανθρώπων, κυρίως μαθητών και φοιτητών με μέσο όρο ηλικίας τα 19 χρόνια.
			</span><br /><br />
			
			<span class="question">
			Γιατί να διαφημιστείτε στο chit-chat;
			</span><br />
			<span class="answer">
			To site μας εξελίσεται ταχύτατα τον τελευταίο καιρό με μεγάλο αριθμό προβολών σε καθημερινή βάση και με υποσχέσεις για 
			ένα ακόμα καλύτερο μέλλον. Επίσης, ο μικρός μέσος όρος ηλικίας, το υψηλό πνευματικό επίπεδο των χρηστών και οι χαμηλές τιμές καθιστούν 
			το chit-chat ιδανική περίπτωση για την προβολή της εταιρείας σας.
			</span><br/><br />
			<span class="question">
			Δυνατότητες διαφήμισης
			</span><br/>
			Στο chit-chat υπάρχει δυνατότητα να διαφημιστείτε σε τέσσερα διαφορετικά σημεία με banners διαστάσεων 370x80 pixels. Τα σημεία που μπορούν να
			τοποθετηθούν τα banners είναι η κεντρική σελίδα, το προφίλ των χρηστών, η προβολή φωτογραφιών και τα άρθρα. Η ελάχιστη συχνότητα εμφάνισης
			των banners σας σε σχέση με τα άλλα που εμφανίζονται ταυτόχρονα είναι 50%.<br /><br />
			Τιμές
			<ul>
				<li>Κεντρική σελίδα</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">250&euro;</span><br />
					<span class="month">3 μήνες</span> <span class="price">220&euro;/μήνα</span>
				</div>
				<li>Προφίλ χρηστών</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">220&euro;</span><br />
					<span class="month">3 μήνες</span> <span class="price">200&euro;/μήνα</span>
				</div>
				<li>Προβολή φωτογραφιών</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">200&euro;</span><br />
					<span class="month">3 μήνες</span> <span class="price">180&euro;/μήνα</span>
				</div>
				<li>Άρθρα</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">150&euro;</span>
				</div>
			</ul>
			Ειδικές προσφορές
			<ul>
				<li>Κεντρική σελίδα και προφίλ χρηστών</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">400&euro;</span><br />
					<span class="month">3 μήνες</span> <span class="price">350&euro;/μήνα</span>
				</div>
				<li>Κεντρική σελίδα, προφίλ χρηστών και προβολή φωτογραφιών</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">600&euro;</span><br />
					<span class="month">3 μήνες</span> <span class="price">550&euro;/μήνα</span>
				</div>
				<li>Κεντρική σελίδα, προφίλ χρηστών, προβολή φωτογραφιών και άρθρα</li>
				<div class="pricelist">
					<span class="month">1 μήνας</span> <span class="price">750&euro;</span><br />
					<span class="month">3 μήνες</span> <span class="price">650&euro;/μήνα</span>
				</div>
			</ul>
			<br />
			<span class="question">
			Επικοινωνία
			</span><br />
			<span style="font-family:serif;">Email</span><br />
			<input type="text" style="width:250px;" /><br /><br />
		    <span style="font-family:serif;">Σχόλια</span><br />
			<textarea style="width:400px;height:200px;" /><br /><br />
			<a href="" onclick="advertise.SendEmail();return false;">&#187;Αποστολή</a>
			
		</div><?php
	}
?>