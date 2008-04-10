<?php
	function ElementUserSettingsPersonal() {
		global $water;
		?><div>
			<label for="dateofbirth">Ημερομηνία Γέννησης:</label>
			<div class="setting" id="dateofbirth">
				<select name="day" class="small">
					<option value="-1">-</option><?php
					for ( $i = 1; $i <= 31; ++$i ) {
						?><option value="<?php
						if ( $i <= 9 ) {
							?>0<?php
						}
						echo $i;
						?>"><?php
						if ( $i <= 9 ) {
							?>0<?php
						}
						echo $i;
						?></option><?php
					}
				?></select>
				<select name="month" class="small">
					<option value="-1">-</option>
					<option value="01">Ιανουαρίου</option>
					<option value="02">Φεβρουαρίου</option>
					<option value="03">Μαρτίου</option>
					<option value="04">Απριλίου</option>
					<option value="05">Μαϊου</option>
					<option value="06">Ιουνίου</option>
					<option value="07">Ιουλίου</option>
					<option value="08">Αυγούστου</option>
					<option value="09">Σεπτεμβρίου</option>
					<option value="10">Οκτωβρίου</option>
					<option value="11">Νοεμβρίου</option>
					<option value="12">Δεκεμβρίου</option>
				</select>
				<select name="month" class="small">
					<option value="-">-</option><?php
					for ( $i = 2001; $i >= 1950; --$i ) {
						?><option value="<?php
						echo $i;
						?>"><?php
						echo $i;
						?></option><?php
					}
				?></select>
			</div>
		</div>
		<div>
			<label for="gender">Φύλο:</label>
			<div class="setting">
				<select id="gender">
					<option value="-">-</option>
					<option value="m">Άνδρας</option>
					<option value="f">Γυναίκα</option>
				</select>
			</div>
		</div>
		<div>
			<label for="place">Περιοχή:</label>
			<div class="setting">
				<select name="place" id="place">
					<option value="-1" >-</option><?php
					$finder = New PlaceFinder();
					$places = $finder->FindAll();
					foreach ( $places as $place ) {
						?><option value="<?php
						echo $place->Id;
						?>"><?php
						echo $place->Name;
						?></option><?php
					}
				?></select>
			</div>
		</div>
		<div>
			<label for="education">Εκπαίδευση:</label>
			<div class="setting">
				<select name="education" id="education">
					<option value="-">-</option>
					<option value="elementary">Δημοτικό</option>
					<option value="gymnasium">Γυμνάσιο</option>
					<option value="TEE">ΤΕΕ</option>
					<option value="lyceum">Λύκειο</option>
					<option value="TEI">TEI</option>
					<option value="university">Πανεπιστήμιο</option>
				</select>
			</div>
		</div>
		<div style="display:none">
			<label>Ποιο πανεπιστήμιο;</label>
			<label>Πόλη:</label>
			<div class="setting">
				<select name="uniplace">
					<option value="-1" >-</option>
					<option value="117 " > Άρτα </option><option value="46 " > Άργος </option><option value="109 " > Άγιος Νικόλαος </option><option value="98 " > Άμφισσα </option><option value="146 " > Έδεσσα </option><option value="149 " > Αργοστόλι </option><option value="160 " > Ασπρόπυργος </option><option value="35 " > Αγρίνιο </option><option value="2 " selected="selected" > Αθήνα </option><option value="113 " > Αλεξανδρούπολη </option><option value="159 " > Αμφιλοχία </option><option value="137 " > Βόλος </option><option value="102 " > Βέροια </option><option value="143 " > Γρεβενά </option><option value="112 " > Δράμα </option><option value="37 " > Ερέτρια </option><option value="133 " > Ερμούπολη </option><option value="157 " > Ελευσίνα </option><option value="124 " > Ζάκυνθος </option><option value="110 " > Ηράκλειο </option><option value="120 " > Ηγουμενίτσα </option><option value="154 " > Θήβα </option><option value="107 " > Θεσσαλονίκη </option><option value="1 " > Ιωάννινα </option><option value="155 " > Ιεράπετρα </option><option value="130 " > Κόρινθος </option><option value="26 " > Κύπρος </option><option value="121 " > Κέρκυρα </option><option value="97 " > Καρπενήσι </option><option value="135 " > Καρδίτσα </option><option value="144 " > Καστοριά </option><option value="105 " > Κατερίνη </option><option value="114 " > Καβάλα </option><option value="132 " > Καλαμάτα </option><option value="122 " > Κεφαλλονιά </option><option value="161 " > Κιάτο </option><option value="103 " > Κιλκίς </option><option value="145 " > Κοζάνη </option><option value="115 " > Κομοτηνή </option><option value="136 " > Λάρισα </option><option value="126 " > Λέσβος </option><option value="99 " > Λαμία </option><option value="123 " > Λευκάδα </option><option value="153 " > Ληξούρι </option><option value="100 " > Λιβαδειά </option><option value="147 " > Μυτιλήνη </option><option value="158 " > Μέτσοβο </option><option value="140 " > Μεσολόγγι </option><option value="152 " > Νάουσα </option><option value="129 " > Ναύπλιο </option><option value="116 " > Ξάνθη </option><option value="151 " > Ορεστιάδα </option><option value="119 " > Πρέβεζα </option><option value="141 " > Πύργος </option><option value="139 " > Πάτρα </option><option value="148 " > Πειραιάς </option><option value="101 " > Πολύγυρος </option><option value="134 " > Ρόδος </option><option value="111 " > Ρέθυμνο </option><option value="131 " > Σπάρτη </option><option value="156 " > Σπέτσες </option><option value="150 " > Σύρος </option><option value="127 " > Σάμος </option><option value="106 " > Σέρρες </option><option value="44 " > Σκύρος </option><option value="128 " > Τρίπολη </option><option value="138 " > Τρίκαλα </option><option value="142 " > Φλώρινα </option><option value="125 " > Χίος </option><option value="96 " > Χαλκίδα </option><option value="11 " > Χανιά </option>
				</select>
			</div>
			<label>Σχολή: </label>
			<div class="setting">
				<select name="unischool">
					<option>-</option>
				</select>
			</div>
		</div>
		<div>
			<label for="photo">Φωτογραφία:</label>
			<div class="setting" id="photo">
				<img src="images/avatars/dionyzizb.jpg" alt="dionyziz" />
				<a href="" onclick="return false">Αλλαγή εικόνας</a>
			</div>
		</div>
		<div>
			<label for="sexualorientation">Σεξουαλικές προτιμήσεις:</label>
			<div class="setting">
				<select id="sexualorientation">
					<option>-</option>
					<option value="straight">Straight</option>
					<option value="bi">Bisexual</option>
					<option value="gay">Gay</option><!-- Lesbian if female? -->
				</select>
			</div>
		</div>
		<div>
			<label for="religion">Θρήσκευμα:</label>
			<div class="setting">
				<select id="religion">
					<option value="-">-</option>
					<option value="christian">Χριστιανός</option><!-- χριστιανή if female etc. -->
					<option value="muslim">Μουσουλμάνος</option>
					<option value="atheist">Άθεος</option>
					<option value="agnostic">Αγνωστικιστής</option>
					<option value="nothing">Τίποτα</option>
				</select>
			</div>
		</div>
		<div>
			<label for="politics">Πολιτικές πεποιθήσεις:</label>
			<div class="setting">
				<select id="politics">
					<option>-</option>
					<option value="right">Δεξιά</option>
					<option value="left">Αριστερά</option>
					<option value="center">Κεντροώα</option>
					<option value="radical right">Ακροδεξιά</option>
					<option value="radical left">Ακροαριστερά</option>
					<option value="nothing">Τίποτα</option>
				</select>
			</div>
		</div>
		<div>
			<label for="aboutme">Λίγα λόγια για μένα:</label>
			<div class="setting">
				<textarea id="aboutme"></textarea>
			</div>
		</div>
		<div>
			<label for="mood">Διάθεση:</label>
			<div class="setting">
				<select id="mood">
					<option>-</option>
					<option>Είμαι χαρούμενος</option> <!-- /η -->
					<option>Είμαι λυπημένος</option>
					<option>Τρέχω και δεν φτάνω</option>
					<option>Είμαι ερωτευμένος</option>
					<option>Φοβάμαι</option>
					<option>Βαριέμαι</option>
					<option>Τα έχω πάρει στο κρανίο</option>
					<option>Δε με νοιάζει τίποτα</option>
					<option>Δεν καταλαβαίνω τι μου συμβαίνει</option>
					<option>Δεν ξέρω τι να κάνω</option>
					<option>Ζηλεύω</option>
					<option>Ντρέπομαι</option>
					<option>Έχω ενθουσιαστεί</option>
					<option>Το φταίξιμο είναι δικό μου</option>
					<option>Ελπίζω</option>
					<option>Είμαι μόνος</option>
					<option>Τους μισώ όλους</option>
					<option>Θέλω να γυρίσω σπίτι</option>
					<option>Με έχει πιάσει υστερία</option>
					<option>Είμαι παρανοϊκός</option>
					<option>Είμαι περίφανος για τον εαυτό μου</option>
					<option>Μετανιώνω γι' αυτό που έκανα</option>
					<option>Ξαφνιάστηκα</option>
					<option>Υποφέρω</option>
				</select>
			</div>
		</div><?php
	}
?>
