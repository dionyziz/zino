<?php
	function ElementUserSettingsPersonalView() {
		global $water;
		global $user;
		global $rabbit_settings;
		
		?><div>
			<label for="dateofbirth">Ημερομηνία Γέννησης:</label>
			<div class="setting" id="dateofbirth"><?php
				Element( 'user/settings/personal/dob' );
			?><span class="invaliddob"><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>exclamation.png" /> Η ημερομηνία δεν είναι έγκυρη
			</span>
			</div>
		</div>
		<div>
			<label for="gender">Φύλο:</label>
			<div class="setting" id="gender"><?php
				Element( 'user/settings/personal/gender' );
			?></div>
		</div>
		<div>
			<label for="place">Περιοχή:</label>
			<div class="setting" id="place"><?php
				Element( 'user/settings/personal/place' );
			?></div>
		</div>
		<div>
			<label for="education">Εκπαίδευση:</label>
			<div class="setting" id="education"><?php
				Element( 'user/settings/personal/education' );
			?></div>
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
			<div class="setting" id="photo"><?php
				Element( 'user/settings/personal/avatar' );
			?></div>
		</div>
		<div>
			<label for="sexualorientation">Σεξουαλικές προτιμήσεις:</label>
			<div class="setting" id="sex"><?php
				Element( 'user/settings/personal/sex' , $user->Profile->Sexualorientation , $user->Gender );
			?></div>
		</div>
		<div>
			<label for="religion">Θρήσκευμα:</label>
			<div class="setting" id="religion"><?php
				Element( 'user/settings/personal/religion' , $user->Profile->Religion , $user->Gender );
			?></div>
		</div>
		<div>
			<label for="politics">Πολιτικές πεποιθήσεις:</label>
			<div class="setting" id="politics"><?php
				Element( 'user/settings/personal/politics' , $user->Profile->Politics , $user->Gender );
			?></div>
		</div>
		<div>
			<label for="aboutme">Λίγα λόγια για μένα:</label>
			<div class="setting" id="aboutme"><?php
				Element( 'user/settings/personal/aboutme' );
			?></div>
		</div>
		<div>
			<label for="mood">Διάθεση:</label>
			<div class="setting" id="mood"><?php
				Element( 'user/settings/personal/mood' );
			?></div>
		</div><?php
	}
?>
