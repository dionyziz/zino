<?php
	function ElementUserSettingsSettings() {
		?>
		<div class="option">
			<span>Αλλαγή κωδικού πρόσβασης</span>
			<div id="oldpassword">
				<span>Κωδικός πρόσβασης:</span><input type="password" />
				<span class="wrongpwd">Ο κωδικός πρόσβασης δεν είναι σωστός</span>
			</div>
			<div id="newpassword">
				<span>Νέος κωδικός πρόσβασης:</span><input type="password" />
				<span class="shortpwd">Πρέπει να δώσεις έναν κωδικό πρόσβασης με τουλάχιστον 4 χαρακτήρες!</span>
			</div>
			<div id="renewpassword">
				<span>Επιβεβαίωση νέου κωδικού:</span><input type="password" />
				<span class="wrongrepwd">Δεν έχεις πληκτρολογήσει σωστά τον κωδικό πρόσβασης!</span>
			</div>
		</div>
		<label>Να λαμβάνω ειδοποιήσεις:</label>
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
		</div><?php
	}
?>
