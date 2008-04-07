<?php
	function ElementUserSettingsSidebar() {
		?><ol id="settingslist">
			<li class="personal"><a href="?p=settings#personal">Πληροφορίες</a></li>
			<li class="characteristics"><a href="?p=settings#characteristics">Χαρακτηριστικά</a></li>
			<li class="interests"><a href="?p=settings#interests">Ενδιαφέροντα</a></li>
			<li class="contact"><a href="?p=settings#contact">Επικοινωνία</a></li>
			<li class="settings"><a href="?p=settings#settings">Ρυθμίσεις</a></li>
		</ol>
		<span>Οι επιλογές σου αποθηκεύτηκαν αυτόματα</span>
		<a class="backtoprofile" href="" onclick="return false">Επιστροφή στο προφίλ</a><?php
	}
?>