<?php

	function ElementUserNew( 
            tBoolean $nopassword, tBoolean $passwordmismatch, 
            tBoolean $usernametaken, tBoolean $usernameexists, 
            tBoolean $usernameinvalid, tBoolean $emailexists,
            tBoolean $screwyou
        ) {
		global $rabbit_settings;
        global $xc_settings;
		global $page;
		global $user;
		
		$page->AttachStylesheet( 'css/rounded.css' );
		$page->SetTitle( "Νέος Χρήστης" );
		
		$nopassword = $nopassword->Get();
		$passwordmismatch = $passwordmismatch->Get();
		$usernametaken = $usernametaken->Get();
		$usernameexists = $usernameexists->Get();
		$usernameinvalid = $usernameinvalid->Get();
		$emailexists = $emailexists->Get();
        $screwyou = $screwyou->Get(); // too many accounts from the same IP in a short period of time
		//--------------------------
		
		?><br /><br /><br /><br /><br /><br /><br /><?php
		
		if ( $passwordmismatch ) {
			?><b>Οι δύο κωδικοί που πληκτρολόγησες δεν είναι οι ίδιοι. Πρέπει να γράψεις τον ίδιο κωδικό δύο φορές!</b><?php
		}
		else if ( $usernameexists ) {
			?><b>Το όνομα χρήστη που επέλεξες χρησιμοποιείται από άλλον. Δοκίμασε κάποιο άλλο όνομα χρήστη.</b><?php
		}
		else if ( $usernameinvalid ) {
			?><b>Το όνομα χρήστη μπορεί να περιέχει μόνο πεζούς και κεφαλαίους λατινικούς χαρακτήρες καθώς και τον χαρακτήρα _ (underscore).<br />Επίσης, θα πρέπει να έχει τουλάχιστον τρία γράμματα.</b><?php
		}
		else if ( $nopassword ) {
			?><b>Δεν έχεις πληκτρολογήσει κωδικό πρόσβασης.</b><?php
		}
		else if ( $emailexists ) {
			?><b>Το email που πληκτρολόγησες ανήκει σε κάποιον άλλο χρήστη του <?php
			echo $rabbit_settings[ 'applicationname' ];
			?>.</b><?php
		}
        else if ( $screwyou ) {
            ?><b>Δεν ήταν δυνατή η δημιουργία λογαριασμού αυτή τη στιγμή. Ξαναδοκίμασε σε 2 λεπτά.</b><?php
        }
		?>
		<br /><br /><?php
		if ( !( $user->IsAnonymous() ) ) {
            ?><b>Έχεις ήδη εισέλθει στο <?php
            echo $rabbit_settings[ 'applicationname' ]; 
            ?>.</b><?php
		}
		else {
			?>
			<div class="content">
				<form action="do/user/new" method="POST" id="createuser">
				<div class="register">
					<div class="opties">
						<div class="upperline">
							<div class="leftupcorner"></div>
							<div class="rightupcorner"></div>
							<div class="middle"></div>
						</div>
						<div class="rectanglesopts">
							<img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>no1tip.png" />
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
						<div class="rectanglesopts">
							<img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>no2tip.png" />
							<span class="directions">Επέλεξε έναν κωδικό πρόσβασης</span><br />
							<span class="tip">(θα τον πληκτρολογείς για να επιβεβαιώνεις την ταυτότητά σου)</span><br />
							<input type="password" tabindex="0" name="password" /><br />
							<br />

					<br />
					
							<span class="directions" style="padding-left:20px">Ξαναγράψε τον κωδικό πρόσβασης</span><br />
							<span class="tip" style="padding-left:20px">(για να βεβαιωθείς ότι δεν έκανες λάθος)</span><br />
							<input type="password" tabindex="0" name="password2" /><br /><br />
							<span class="littletip">Η δημιουργία λογαριασμού συνεπάγεται την απόλυτη κατανόηση και αποδοχή των <a style="color:#3568eb" href="?p=tos" tabindex="1">όρων χρήσης.</a></span>
							<a href="?p=faqc&amp;id=7" style="font-size:80%;">Πληροφορίες για τον κωδικό πρόσβασης</a><br />
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
						<div class="rectanglesopts">
							<img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>no3tip.png" />
							<span class="directions">Πληκτρολόγησε το email σου</span><br />
							<span class="tip">(άμα ξεχάσεις τον κωδικό σου θα σου στείλουμε έναν νέο εκεί)</span><br />
							<input type="text" tabindex="0" name="email"/><br /><br />
							<span class="littletip">Δε θα χρησιμοποιήσουμε το email σου για άλλους σκοπούς χωρίς τη δική σου άδεια.</span>
							<a href="faq/whymail_spam_showmail" style="font-size:80%;">Γιατί ζητάτε το email μου?</a><br />
						</div>
						<div class="downline">
							<div class="leftdowncorner"></div>
							<div class="rightdowncorner"></div>
							<div class="middledowncss"></div>
						</div>
					</div>
				</div>
				<div id="nextlink" style="text-align:center"><a href="" onclick="g('createuser').submit();return false" class="next">Συνέχεια &gt;&gt;</a></div>
				</form>
			</div>
			<?php
		}
	}
?>
