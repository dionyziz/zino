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
						<span>Ημερομηνία γέννησης:</span><?php
						Element( 'user/settings/personal/dob' , $user );
					?></div>
					<div>
						<span>Φύλο:</span><?php
						Element( 'user/settings/personal/gender' , $user );
					?></div>
					<div>
						<span>Περιοχή:</span><?php
						Element( 'user/settings/personal/place' , $user );
					?></div>
				</form>
			</div>
			<div style="text-align:center;">
				<a href="" class="button button_big">Συνέχεια &raquo;</a>
			</div>
		</div><?php
}
