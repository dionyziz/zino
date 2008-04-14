<?php
	function ElementUserSettingsSidebar() {
		global $rabbit_settings;
		
		?><ol id="settingslist">
			<li class="personal"><a href="">Πληροφορίες</a></li>
			<li class="characteristics"><a href="">Χαρακτηριστικά</a></li>
			<li class="interests"><a href="">Ενδιαφέροντα</a></li>
			<li class="contact"><a href="">Επικοινωνία</a></li>
			<li class="settings"><a href="">Ρυθμίσεις</a></li>
		</ol>
		<div>
			<span class="saving"><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>ajax-loader.gif" /> Αποθήκευση...
			</span>
			<span class="saved">Οι επιλογές σου αποθηκεύτηκαν αυτόματα</span>
		</div>
		<a class="backtoprofile" href="" onclick="return false">Επιστροφή στο προφίλ</a><?php
	}
?>