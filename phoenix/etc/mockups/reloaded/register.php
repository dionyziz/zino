<?php
	include "banner.php";
	$nopassword = isset( $_GET[ "nopassword" ] );
	$passwordmismatch = isset( $_GET[ "passwordmismatch" ] );
	$usernametaken = isset( $_GET[ "usernametaken" ] );
	$usernameexists = isset( $_GET[ "usernameexists" ] );
	$usernameinvalid = ( isset( $_GET[ "usernameinvalid" ] ) ? $_GET[ "usernameinvalid" ] : "no" );
	//$emailexists = ( isset( $_GET[ "emailexists" ] ) ? $_GET[ "emailexists" ] : "yes" );
	if ( isset( $_GET[ "emailexists" ] ) ) {
		$emailexists = "yes";
	}
	else {
		$emailexists = "no";
	}
	
	?>
	
	<br /><br /><br /><br /><br /><br /><br /><?php
	if ( $passwordmismatch ) {
		?><b>Οι δύο κωδικοί που πληκτρολόγησες δεν είναι οι ίδιοι. Πρέπει να γράψεις τον ίδιο κωδικό δύο φορές!</b><?php
	}
	elseif ( $usernameexists ) {
		?>
		<b>Το όνομα χρήστη που επέλεξες χρησιμοποιείται από άλλον. Δοκίμασε κάποιο άλλο όνομα χρήστη.</b><?php
	}
	elseif ( $usernameinvalid == "yes" ) {
		?>
		<b>Το όνομα χρήστη μπορεί να περιέχει μόνο πεζούς και κεφαλαίους λατινικούς χαρακτήρες καθώς και τον χαρακτήρα _ (underscore).<br />Επίσης, θα πρέπει να έχει τουλάχιστον τρία γράμματα.</b><?php
	}
	elseif ( $nopassword == "yes" ) {
		?><b>Δεν έχεις πληκτρολογήσει κωδικό πρόσβασης.</b><?php
	}
	elseif( $emailexists=="yes" ) {
		?>
		<b>Το email που πληκτρολόγησες ανήκει σε κάποιον άλλο χρήστη του</b><?php
	echo $xc_settings[ 'name' ];
?>.<?php
	}
?>
<br /><br />
<div class="content">
	<form action="newuser.php" method="POST" id="createuser">
	<div class="register">
		<div class="opties">
			<div class="upperline">
				<div class="leftupcorner"></div>
				<div class="rightupcorner"></div>
				<div class="middle"></div>
			</div>
			<div class="registeropts">
				<img src="no1tip.png" />
				<span class="directions">Διάλεξε ένα όνομα χρήστη</span><br />
				<span class="tip">(με αυτό το όνομα θα εμφανίζεσαι στο Chit-Chat)</span><br />
				<input type="text" tabindex="0" name="username" /><br />
				<span class="littletip">Αν έχεις ήδη λογαριασμό, πληκτρολόγησε τα στοιχεία σου πάνω δεξιά για να εισέλθεις.</span><br />
			</div>
			<div class="downline">
				<div class="leftdowncorner"></div>
				<div class="rightdowncorner"></div>
				<div class="middledowncss"></div>
			</div>
		</div>
		<br />
				
		<div class="opties">
			<div class="upperline">
				<div class="leftupcorner"></div>
				<div class="rightupcorner"></div>
				<div class="middle"></div>
			</div>
			<div class="registeropts">
				<img src="no2tip.png" />
				<span class="directions">Επέλεξε έναν κωδικό πρόσβασης</span><br />
				<span class="tip">(θα τον πληκτρολογείς για να επιβεβαιώνεις την ταυτότητά σου)</span><br />
				<input type="password" tabindex="0" name="password" /><br />
				<br />

		<br />
		
				<span class="directions" style="padding-left:20px">Ξαναγράψε τον κωδικό πρόσβασης</span><br />
				<span class="tip" style="padding-left:20px">(για να βεβαιωθείς ότι δεν έκανες λάθος)</span><br />
				<input type="password" tabindex="0" name="password2" /><br />
				<span class="littletip">Η δημιουργία λογαριασμού συνεπάγεται την απόλυτη κατανόηση και αποδοχή των <a style="color:#3568eb" href="?p=tos" tabindex="1">όρων χρήσης.</a></span><br />
			</div>
			<div class="downline">
				<div class="leftdowncorner"></div>
				<div class="rightdowncorner"></div>
				<div class="middledowncss"></div>
			</div>
		</div>
		<br />
		
		<div class="opties">
			<div class="upperline">
				<div class="leftupcorner"></div>
				<div class="rightupcorner"></div>
				<div class="middle"></div>
			</div>
			<div class="registeropts">
				<img src="no3tip.png" />
				<span class="directions">Πληκτρολόγησε το email σου</span><br />
				<span class="tip">(άμα ξεχάσεις τον κωδικό σου θα σου στείλουμε έναν νέο εκεί)</span><br />
				<input type="text" tabindex="0" name="email"/><br />
				<span class="littletip">Δε θα χρησιμοποιήσουμε το email σου για άλλους σκοπούς πέρα από τη δική σου άδεια.</span><br />
			</div>
			<div class="downline">
				<div class="leftdowncorner"></div>
				<div class="rightdowncorner"></div>
				<div class="middledowncss"></div>
			</div>
		</div>
	</div>
	<div id="nextlink" style="text-align:center"><a href="" onclick="g('createuser').submit();return false" class="next">Συνέχεια >></a></div>
	</form>
</div>
