<?php

	function	ElementUserJoined() {
		global $user;
		global $rabbit_settings;
		global $page;
		
		//$page->AttachStyleSheet( 'css/user/joined.css' );
		//$page->AttachStyleSheet( 'css/bubbles.css' );
		//$page->AttachScript( 'js/user/joined.js' );
		$finder = New PlaceFinder();
		$places = $finder->FindAll();
		
		$page->SetTitle( 'Καλωσήρθες στο ' . $rabbit_settings[ 'applicationname' ] );
		if ( !$user->Exists() ) {
			Redirect( $rabbit_settings[ 'webaddress' ] );
		}
		?><div id="joined">
			<div class="ybubble">
		        <div>Συγχαρητήρια! Mόλις δημιουργήσες λογαριασμό στο <?php
				echo $rabbit_settings[ 'applicationname' ];
				?>.<br />
				To προφίλ σου είναι <a href="<?php
				Element( 'user/url' , $user );
				?>"><?php
				Element( 'user/url' , $user );
				?></a>.</div>
		        <i class="bl"></i>
		        <i class="br"></i>
		    </div>
			<div class="profinfo">
				<div class="logininfo">
					<img src="images/login-screenshot.jpg" style="width:300px;height:182px;" />
					<span>Μπορείς να κάνεις είσοδο από την κεντρική σελίδα.</span>
				</div>
			</div>
			<div class="profinfo">
				<p>
				Συμπλήρωσε μερικές λεπτομέρειες για τον εαυτό σου.<br />Αν δεν θες να το κάνεις τώρα,
				μπορείς αργότερα από το προφίλ σου.
				</p>
				<form>
					<div>
						<span>Ημερομηνία γέννησης:</span>
						<select>
							<option value="0">Ημέρα</option><?php
							for ( $i = 1; $i <= 31; ++$i ) {
								?><option value="<?php
								echo $i;
								?>"><?php
								echo $i;
								?></option><?php
							}
							?>
						</select>
						<select>
							<option value="0">Μήνας</option>
							<option value="1">Ιανουάριος</option>
							<option value="2">Φεβρουάριος</option>
							<option value="3">Μάρτιος</option>
							<option value="4">Απρίλιος</option>
							<option value="5">Μάιος</option>
							<option value="6">Ιούνιος</option>
							<option value="7">Ιούλιος</option>
							<option value="8">Αύγουστος</option>
							<option value="9">Σεπτέμβριος</option>
							<option value="10">Οκτώβριος</option>
							<option value="11">Νοέμβριος</option>
							<option value="12">Δεκέμβριος</option>
						</select>
						<select>
							<option value="0" selected="selected">Έτος</option><?php
							for ( $i = 1940; $i <= 2000; ++$i ) {
								?><option value="<?php
								echo $i;
								?>"><?php
								echo $i;
								?></option><?php
							}
							?>
						</select>
					</div>
					<div>
						<span>Φύλο:</span>
						<select>
							<option value="-" selected="selected">-</option>
							<option value="m">Άντρας</option>
							<option value="f">Γυναίκα</option>
						</select>
					</div>
					<div>
						<span>Περιοχή:</span>
						<select>
							<option value="0"<?php
							if ( $user->Profile->Location->Id == 0 ) {
								?> selected="selected"<?php
							}
							?>>-</option><?php
							foreach( $places as $place ) {
								?><option value="<?php
								echo $place->Id;
								?>"<?php
								if ( $user->Profile->Location->Id == $place->Id ) {
									?> selected="selected"<?php
								}
								?>><?php
								echo $place->Name;
								?></option><?php
							}
		                ?></select>
					</div>
				</form>
			</div>
			<div style="text-align:center;">
				<a href="" class="button button_big">Συνέχεια &raquo;</a>
			</div>
		</div><?php
}
