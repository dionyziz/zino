<?php
	function ElementUserSettingsPersonalView() {
		global $water;
		global $user;
		global $rabbit_settings;
		
		?><div class="option">
			<label for="dateofbirth">Ημερομηνία Γέννησης:</label>
			<div class="setting" id="dateofbirth"><?php
				Element( 'user/settings/personal/dob' );
			?><span class="invaliddob"><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>exclamation.png" /> Η ημερομηνία δεν είναι έγκυρη
			</span>
			</div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="gender">Φύλο:</label>
			<div class="setting" id="gender"><?php
				Element( 'user/settings/personal/gender' );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="place">Περιοχή:</label>
			<div class="setting" id="place"><?php
				Element( 'user/settings/personal/place' );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="education">Εκπαίδευση:</label>
			<div class="setting" id="education"><?php
				Element( 'user/settings/personal/education' );
			?><div class="forstudents">Αν είσαι φοιτητής όρισε την περιοχή και το είδος του εκπαιδευτικού ιδρύματος</div>
			</div>
			<label>Ποιο πανεπιστήμιο;</label>
			<div class="setting" id="university">
				<div class="setting"><?php
					if ( $user->Profile->Education == 'university' ) {
						$typeid = 0;
					}
					else if( $user->Profile->Education == 'TEI' ) {
						$typeid  = 1;
					}
					Element( 'user/settings/personal/university' , $user->Profile->Placeid , $typeid );
				?></div>
			</div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="photo">Φωτογραφία:</label>
			<div class="setting" id="photo"><?php
				Element( 'user/settings/personal/avatar' );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="sexualorientation">Σεξουαλικές προτιμήσεις:</label>
			<div class="setting" id="sex"><?php
				Element( 'user/settings/personal/sex' , $user->Profile->Sexualorientation , $user->Gender );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="religion">Θρήσκευμα:</label>
			<div class="setting" id="religion"><?php
				Element( 'user/settings/personal/religion' , $user->Profile->Religion , $user->Gender );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="politics">Πολιτικές πεποιθήσεις:</label>
			<div class="setting" id="politics"><?php
				Element( 'user/settings/personal/politics' , $user->Profile->Politics , $user->Gender );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="aboutme">Λίγα λόγια για μένα:</label>
			<div class="setting" id="aboutme"><?php
				Element( 'user/settings/personal/aboutme' );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div>
		<div class="option">
			<label for="mood">Διάθεση:</label>
			<div class="setting" id="mood"><?php
				Element( 'user/settings/personal/mood' );
			?></div>
		</div>
		<div class="barfade">
			<div class="leftbar"></div>
			<div class="rightbar"></div>
		</div><?php
	}
?>
