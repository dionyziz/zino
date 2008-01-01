<div id="joined">
	<div class="ybubble">
        <div>Συγχαρητήρια! Mόλις δημιουργήσες λογαριασμό στο chit-chat.<br />
		To προφίλ σου είναι <a href="http://dionyziz.chit-chat.gr">dionyziz.chit-chat.gr</a>.</div>
        <i class="bl"></i>
        <i class="br"></i>
    </div>
	<div class="profinfo">
		<div class="logininfo">
			<img src="images/login-screenshot.jpg" style="width:300px;height:182px;" alt="Screenshot" />
			<span>Μπορείς να κάνεις είσοδο από την κεντρική σελίδα.</span>
		</div>
	</div>
	<div class="profinfo">
		<p>
		Συμπλήρωσε μερικές λεπτομέρειες για τον εαυτό σου.<br />Αν δεν θες να το κάνεις τώρα,
		μπορείς αργότερα από το προφίλ σου.
		</p>
		<form action="">
			<div>
				<span>Ημερομηνία γέννησης:</span>
				<select>
					<option value="0">Ημέρα</option><?php
					for ( $i = 1; $i <= 31; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"><?php
						echo $i;
						?></option><?php
					}
					?>
				</select>
				<select>
					<option value="0">Μήνας</option>
					<option value="1">Ιανουάριος</option>
					<option value="2">Φεβρουάριος</option>
					<option value="3">Μάρτιος</option>
					<option value="4">Απρίλιος</option>
					<option value="5">Μάιος</option>
					<option value="6">Ιούνιος</option>
					<option value="7">Ιούλιος</option>
					<option value="8">Αύγουστος</option>
					<option value="9">Σεπτέμβριος</option>
					<option value="10">Οκτώβριος</option>
					<option value="11">Νοέμβριος</option>
					<option value="12">Δεκέμβριος</option>
				</select>
				<select>
					<option value="0" selected="selected">Έτος</option><?php
					for ( $i = 1940; $i <= 2000; ++$i ) {
						?><option value="<?php
						echo $i;
						?>"><?php
						echo $i;
						?></option><?php
					}
					?>
				</select>
			</div>
			<div>
				<span>Φύλο:</span>
				<select>
					<option value="0" selected="selected">-</option>
					<option value="boy">αγόρι</option>
					<option value="man">άντρας</option>
					<option value="girl">κορίτσι</option>
					<option value="woman">γυναίκα</option>
				</select>
			</div>
			<div>
				<span>Περιοχή:</span>
				<select>
                    <option value="0" selected="selected">-</option>
                    <option value="117">Άρτα</option><option value="46">Άργος</option><option value="109">Άγιος Νικόλαος</option><option value="98">Άμφισσα</option><option value="146">Έδεσσα</option><option value="149">Αργοστόλι</option><option value="160">Ασπρόπυργος</option><option value="35">Αγρίνιο</option><option value="2">Αθήνα</option><option value="113">Αλεξανδρούπολη</option><option value="159">Αμφιλοχία</option><option value="137">Βόλος</option><option value="102">Βέροια</option><option value="143">Γρεβενά</option><option value="112">Δράμα</option><option value="37">Ερέτρια</option><option value="133">Ερμούπολη</option><option value="157">Ελευσίνα</option><option value="124">Ζάκυνθος</option><option value="110">Ηράκλειο</option><option value="120">Ηγουμενίτσα</option><option value="154">Θήβα</option><option value="107">Θεσσαλονίκη</option><option value="1">Ιωάννινα</option><option value="155">Ιεράπετρα</option><option value="130">Κόρινθος</option><option value="26">Κύπρος</option><option value="121">Κέρκυρα</option><option value="97">Καρπενήσι</option><option value="135">Καρδίτσα</option><option value="144">Καστοριά</option><option value="105">Κατερίνη</option><option value="114">Καβάλα</option><option value="132">Καλαμάτα</option><option value="122">Κεφαλλονιά</option><option value="161">Κιάτο</option><option value="103">Κιλκίς</option><option value="145">Κοζάνη</option><option value="115">Κομοτηνή</option><option value="136">Λάρισα</option><option value="126">Λέσβος</option><option value="99">Λαμία</option><option value="123">Λευκάδα</option><option value="153">Ληξούρι</option><option value="100">Λιβαδειά</option><option value="147">Μυτιλήνη</option><option value="158">Μέτσοβο</option><option value="140">Μεσολόγγι</option><option value="152">Νάουσα</option><option value="129">Ναύπλιο</option><option value="116">Ξάνθη</option><option value="151">Ορεστιάδα</option><option value="119">Πρέβεζα</option><option value="141">Πύργος</option><option value="139">Πάτρα</option><option value="148">Πειραιάς</option><option value="101">Πολύγυρος</option><option value="134">Ρόδος</option><option value="111">Ρέθυμνο</option><option value="131">Σπάρτη</option><option value="156">Σπέτσες</option><option value="150">Σύρος</option><option value="127">Σάμος</option><option value="106">Σέρρες</option><option value="44">Σκύρος</option><option value="128">Τρίπολη</option><option value="138">Τρίκαλα</option><option value="142">Φλώρινα</option><option value="125">Χίος</option><option value="96">Χαλκίδα</option><option value="11">Χανιά</option>
                </select>
			</div>
		</form>
	</div>
	<div style="text-align:center;">
		<a href="" class="button">Συνέχεια &raquo;</a>
	</div>
</div>
