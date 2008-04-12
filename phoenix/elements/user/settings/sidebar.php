<?php
	function ElementUserSettingsSidebar() {
		global $rabbit_settings;
		
		?><ol id="settingslist">
			<li class="personal"><a href="?p=settings#personal">Πληροφορίες</a></li>
			<li class="characteristics"><a href="?p=settings#characteristics">Χαρακτηριστικά</a></li>
			<li class="interests"><a href="?p=settings#interests">Ενδιαφέροντα</a></li>
			<li class="contact"><a href="?p=settings#contact">Επικοινωνία</a></li>
			<li class="settings"><a href="?p=settings#settings">Ρυθμίσεις</a></li>
		</ol>
		<span class="saving"><img src="<?php
		echo $rabbit_settings[ 'imagesurl' ];
		?>ajax-loader.gif" /> Αποθήκευση...
		</span>
		<span class="saved">Οι επιλογές σου αποθηκεύτηκαν αυτόματα</span>
		<a class="backtoprofile" href="" onclick="return false">Επιστροφή στο προφίλ</a><?php
	}
?>