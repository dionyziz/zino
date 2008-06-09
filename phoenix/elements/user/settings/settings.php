<?php
	function ElementUserSettingsSettings() {
		global $rabbit_settings;
		?><div><label><a class="changepwdlink" href="">Αλλαγή κωδικού πρόσβασης</a></label></div>
		<div><label>Να λαμβάνω ειδοποιήσεις</label></div>
		<div class="setting">
			<table>
				<thead>
					<tr>
						<th></th>
						<th>Μέσω e-mail</th>
						<th>Μέσα στο site</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Σχόλια στο προφίλ μου:</th>
						<td><input type="checkbox" /></td>
						<td><input type="checkbox" /></td>
					</tr>
					<tr>
						<th>Σχόλια στις εικόνες μου:</th>
						<td><input type="checkbox" /></td>
						<td><input type="checkbox" /></td>
					</tr>
					<tr>
						<th>Σχόλια στις δημοσκοπίσεις και στα ημερολόγιά μου:</th>
						<td><input type="checkbox" /></td>
						<td><input type="checkbox" /></td>
					</tr>
					<tr>
						<th>Απαντήσεις στα σχόλιά μου:</th>
						<td><input type="checkbox" /></td>
						<td><input type="checkbox" /></td>
					</tr>
					<tr>
						<th>Νέοι φίλοι:</th>
						<td><input type="checkbox" /></td>
						<td><input type="checkbox" /></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="changepwd">
			<h3>Αλλαγή κωδικού πρόσβασης</h3>
			<div class="oldpassword">
				<span>Κωδικός πρόσβασης:</span>
				<div>
					<input type="password" />
					<div class="wrongpwd">
					<span>
					<img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Ο κωδικός πρόσβασης δεν είναι σωστός
					</span>
					</div>
				</div>
			</div>
			<div class="newpassword">
				<span>Νέος κωδικός πρόσβασης:</span>
				<div>
					<input type="password" />
					<div class="shortpwd">
					<span>
					<img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Πρέπει να δώσεις έναν κωδικό πρόσβασης με τουλάχιστον 4 χαρακτήρες!
					</span>
					</div>
				</div>
			</div>
			<div class="renewpassword">
				<span>Επιβεβαίωση νέου κωδικού:</span>
				<div>
					<input type="password" />
					<div class="wrongrepwd">
					<span><img src="<?php
					echo $rabbit_settings[ 'imagesurl' ];
					?>exclamation.png" alt="Προσοχή" title="Προσοχή" />Δεν έχεις πληκτρολογήσει σωστά τον κωδικό πρόσβασης!
					</span>
					</div>
				</div>
			</div>
			<div class="save">
				<a href="" class="button save">Αποθήκευση</a>
				<a href="" class="button cancel">Ακύρωση</a>
			</div>
		</div><?php
	}
?>
