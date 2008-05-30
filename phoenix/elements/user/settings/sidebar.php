<?php
	function ElementUserSettingsSidebar() {
		global $rabbit_settings;
		global $user;
		
		?><ol id="settingslist">
			<li class="personal"><a href="" onclick="Settings.SwitchSettings( 'personal' );return false;">Πληροφορίες</a></li>
			<li class="characteristics"><a href="" onclick="Settings.SwitchSettings( 'characteristics' );return false;">Χαρακτηριστικά</a></li>
			<li class="interests"><a href="" onclick="Settings.SwitchSettings( 'interests' );return false;">Ενδιαφέροντα</a></li>
			<li class="contact"><a href="" onclick="Settings.SwitchSettings( 'contact' );return false;">Επικοινωνία</a></li>
			<li class="settings"><a href="" onclick="Settings.SwitchSettings( 'settings' );return false;">Ρυθμίσεις</a></li>
		</ol>
		<div>
			<span class="saving"><img src="<?php
			echo $rabbit_settings[ 'imagesurl' ];
			?>ajax-loader.gif" /> Αποθήκευση...
			</span>
			<span class="saved">Οι επιλογές σου αποθηκεύτηκαν αυτόματα</span>
		</div>
		<a class="backtoprofile button" style="padding-top:0;padding-bottom:0;" href="<?php
		Element( 'user/url' , $user );
		?>">Επιστροφή στο προφίλ</a><?php
	}
?>