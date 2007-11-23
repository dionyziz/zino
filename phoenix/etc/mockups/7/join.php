<div class="join">
	<div class="bubble">
	    <i class="tl"></i><i class="tr"></i>
		<h2>Γίνε μέλος!</h2>
		<form class="joinform">
			<div>
				<label for="join_name">Όνομα χρήστη:</label>
				<input type="text" id="join_name" value="" onfocus="Join.Focusinput( this );" onblur="Join.Unfocusinput( this);" />
				<p>Το όνομα με το οποίο θα εμφανίζεσαι, δεν μπορείς να το αλλάξεις αργότερα.</p>
			</div>
			<div>
				<label for="join_pwd">Κωδικός πρόσβασης:</label>
				<input type="password" id="join_pwd" value="" style="margin-bottom:5px;" onfocus="Join.Focusinput( this );" onblur="Join.Unfocusinput( this );" />
				<div>
					<label for="join_repwd">Πληκτρολόγησε τον ξανά:</label>
					<input type="password"  id="join_repwd" value="" style="vertical-align:top;" onfocus="Join.Focusinput( this );" onblur="Join.Unfocusinput( this );" onkeyup="Join.Checkpwd( this );" />
				</div>
			</div>
			<div>
				<label for="join_email">E-mail (προαιρετικά):</label>
				<input type="text" id="join_email" value="" style="width:200px;" onfocus="Join.Focusinput( this );" onblur="Join.Unfocusinput( this );" />
				<p>Αν συμπληρώσεις το e-mail σου θα μπορείς να επαναφέρεις τον κωδικό σου σε περίπτωση που τον ξεχάσεις.</p>
			</div>
			
			<p>Η δημιουργία λογαριασμού συνεπάγεται την ανεπιφύλακτη αποδοχή των <a href="">όρων χρήσης</a>.</p>
		
			<div style="text-align:center;">
				<a href="" class="button">Δημιουργία &raquo;</a>
			</div>
		</form>    
	    <i class="qleft"></i><i class="qright"></i>
	    <i class="qbottom"></i>
	    <i class="bl"></i><i class="br"></i>
	</div>
	<img src="images/button_ok_16.png" alt="Σωστή επαλήθευση" title="Σωστή επαλήθευση" style="display:none;" />
</div>
<script type="text/javascript" src="js/join.js"></script>