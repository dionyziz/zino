<?php
	include "banner.php";
	$xc_settings[ 'webname' ] = "Chit-Chat";
	
?><br /><br /><br /><br /><br />
<div id="settings" class="settings">
	<span class="headings" onclick="SetCat.activate_category( '0' );"><img id="setimg0" src="images/icons/settings-collapsed.png" /> Προσωπικές πληροφορίες
	<img src="images/icons/settings-personalinfo.jpg" /></span><br /><br />
	<div id="cat0" class="userinfo">
		Φύλο: 
		<select id="settings_gender">
			<option value="male">Άνδρας</option>
			<option value="female">Γυναίκα</option>
		</select><br /><br />
		Τοποθεσία: 
		<select id="settings_area">
			<option value="athens">Αθήνα</option>
			<option value="ioannina">Ιωάννινα</option>
			<option value="drama">Δράμα</option>
			<option value="outofspace">Εκτός ηλιακού συστήματος</option>
			<option value="agrinio">Αγρίνιο</option>
		</select><br /><br />
		Ημερομηνία γέννησης:<br /><br />
		Ημέρα: <select id="settings_day"><?php
			for ( $i = 1; $i<=31; $i++ ) { ?>
				<option value="<?php
					echo $i; ?>
					"><?php
					echo $i; ?>
				</option><?php
			} ?>
		</select>
		Μήνας: <select id="settings_month">
			<option value="januar">Ιανουάριος</option>
			<option value="february">Φεβρουάριος</option>
			<option value="march">Μάρτιος</option>
			<option value="april">Απρίλιος</option>
			<option value="may">Μάιος</option>
			<option value="june">Ιούνιος</option>
			<option value="july">Ιούλιος</option>
			<option value="august">Αύγουστος</option>
			<option value="september">Σεπτέμβιος</option>
			<option value="october">Οκτώβριος</option>
			<option value="november">Νοέμβριος</option>
			<option value="december">Δεκέμβριος</option>
		</select>
		Έτος: <select id="settings_year"><?php
			$thisyear = date( 'Y' );
			for ( $i = $thisyear - 5; $i>=1940; --$i ) { ?>
				<option value="<?php
					echo $i; ?>
					"><?php
					echo $i; ?>
				</option><?php
			} ?>
			
		</select><br /><br />
		Ενδιαφέροντα:<br />
		<textarea rows="8" cols="60"></textarea>
		<br /><br />
	</div>
	
	
	<span class="headings" onclick="SetCat.activate_category( '1' );"><img id="setimg1" src="images/icons/settings-collapsed.png" /> Αλλαγή κωδικού πρόσβασης
	<img src="images/icons/settings-password.jpg" /></span><br /><br />
	<div id="cat1" class="password">
		Παλιός Κωδικός: 
		<input type="password" /><br />
		<span class="minitip">(πληκτρολόγησε τον παλιό σου κωδικό)</span>
		<br /><br />
		Νέος Κωδικός: 
		<input type="password" /><br />
		<span class="minitip">(πληκτρολόγησε τον νέο σου κωδικό)</span>
		<br /><br />
		Νέος Κωδικός (ξανά): 
		<input type="password" /><br />
		<span class="minitip">(πληκτρολόγησε τον νέο σου κωδικό ξανά, για να σιγουρευτείς ότι είναι σωστός)</span>
		<br /><br />
	</div>
	
	
	<span class="headings" onclick="SetCat.activate_category( '2' );"><img id="setimg2" src="images/icons/settings-collapsed.png" /> Εικονίδιο</span><br /><br />
	<div id="cat2" class="avatar">
		<img src="images/anonymous.jpg" /><br />
		<span class="minitip">(αν θέλεις να αλλάξεις το εικονίδιό σου, απλώς επέλεξε ένα νέο εικονίδιο και αποθήκευσε)</span><br />
		<form method="POST" enctype="multipart/form-data" action="upload.php" style="padding-top:5px">
			<input type="file" />
		</form><br />
		...ή διάλεξε κάποιο από τα έτοιμα εικονίδια:
		<br /><br />
	</div>
	
	
	<span class="headings" onclick="SetCat.activate_category( '3' );"><img id="setimg3" src="images/icons/settings-collapsed.png" /> Επικοινωνία
	<img src="images/icons/settings-contacts.jpg"  /></span><br /><br />
	<div id="cat3" class="contacts">
		Ε-mail: <input type="text" /><br />
		<span class="minitip">(το e-mail σου είναι χρήσιμο σε περίπτωση που ξεχάσεις τον κωδικό σου)</span><br /><br />
		<img src="images/icons/msn.png" />Όνομα χρήστη MSN: <input type="text" /><br /><br />
		<img src="images/icons/yahoo.png" />Yahoo! ID: <input type="text" /><br /><br />
		<img src="images/icons/aim.png" />Όνομα χρήστη ΑΙΜ: <input type="text" /><br /><br />
		<img src="images/icons/icq.png" />Αριθμός ICQ: <input type="text" /><br /><br />
		<img src="images/icons/gtalk.png" />Google Talk: <input type="text" /><br />
		<span class="minitip">(αν έχεις κάποιο πρόγραμμα Instant Messaging, πληκτρολόγησε το όνομα επικοινωνίας που έχεις, έτσι ώστε οι άλλοι χρήστες του <?php
		echo $xc_settings[ 'webname' ]; ?>
		να μπορούν να σε προσθέσουν στις επαφές τους)</span><br /><br />
	</div>
	
	
	<span class="headings" onclick="SetCat.activate_category( '4' );"><img id="setimg4" src="images/icons/settings-collapsed.png" /> Υπογραφή &amp; Slogan
	<img src="images/icons/settings-signatureslogan.jpg" /></span><br /><br />
	<div id="cat4" class="signatureslogan">
		<span class="sigslog">Υπογραφή</span><br />
		<textarea rows="8" cols="60" style="padding-top:5px" /><br />
		<span class="minitip">(αυτό το κείμενο θα εμφανίζεται κάτω από τα σχόλιά σου)</span><br /><br />
		
		<span class="sigslog">Slogan</span><br />
		<input type="text" size="50" style="padding-top:5px" /><br />
		<span class="minitip">(αυτό το κείμενο θα εμφανίζεται κάτω από το όνομά σου)</span>
		<br /><br />
	</div>
	
	
	<span class="headings" onclick="SetCat.activate_category( '5' );"><img id="setimg5" src="images/icons/settings-collapsed.png" /> Ρυθμίσεις
	<img src="images/icons/settings-specsettings.jpg" /></span><br /><br />
	<div id="cat5" class="specsettings">
		Όταν το παρακάτω πλαίσιο είναι επιλεγμένο, οι εικόνες στα άρθρα θα στέλνονται με χαμηλότερη ανάλυση ώστε να μεταφέρονται πιο γρήγορα.<br /><br />
		<input type="checkbox" /> Άν έχεις αργή σύνδεση επέλεξε το διπλανό πλαίσιο
		<br /><br />
	</div>
</div>

<script type="text/javascript">
var SetCat = {
	activated : -1,
	activate_category: function( catindex ) {
			if ( SetCat.activated != -1 ) {
				document.getElementById( 'cat' + SetCat.activated ).style.display = "none";
				document.getElementById( 'setimg' + SetCat.activated ).src = "images/icons/settings-collapsed.png";
			}
			document.getElementById( 'cat' + catindex ).style.display = "block";	
			document.getElementById( 'setimg' + catindex ).src = "images/icons/settings-expanded.png";
			SetCat.activated = catindex;
	}
}
</script>