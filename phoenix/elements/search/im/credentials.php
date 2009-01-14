<?php    
	class ElementSearchImCredentials extends Element {
        public function Render( tString $im ) {
			global $rabbit_settings;
			global $user;
			global $page;
			if ( $user->Exists() ) {
				$page->SetTitle( 'Βρες τους φίλους σου στο ' . $rabbit_settings[ 'applicationname' ] );
				$im = $im->Get();
				?><div id="im">
					<h2>Βρες τους φίλους σου στο <?php
					echo $rabbit_settings[ 'applicationname' ];
					?></h2>
					<div class="cred">
						<h3>Δώσε το email σου και τον κωδικό πρόσβασης</h3>
						<div class="empwd">	
							<div class="mail">
								<span class="credin1">Εmail</span><input type="text" />@
								<select><?php
								if ( $im == "msn" ) {
									?><option value="hotmail.com" selected="selected">hotmail.com</option>
									<option value="windowslive.com">windowslive.com</option>
									<option value="msn.com">msn.com</option>
									<option value="live.com">live.com</option><?php
								}
								else if ( $im == "gmail" ) {
									?><option value="gmail.com">gmail.com</option>
									<option value="googlemail.com">googlemail.com</option><?php
								}
								else if ( $im == "yahoo" ) {
									?><option value="yahoo.gr">yahoo.gr</option>
									<option value="yahoo.com">yahoo.com</option><?php
								}
								?></select>
							</div>
							<div class="pwd">
								<span class="credin2">Κωδικός</span><input type="password" />
							</div>
						</div>
						<div class="wrong">
							<div>
								<span class="s_invalid">&nbsp;</span>Ο συνδυασμός email και κωδικού πρόσβασης είναι λανθασμένος
							</div>
							<div class="w">
								<img src="<?php
								echo $rabbit_settings[ 'imagesurl' ];
								?>ajax-loader.gif" /> Παρακαλώ περιμένετε...
							</div>
							<div id="nullmail">
								<span class="s_invalid">&nbsp;</span>Πρέπει να συμπληρώσεις το email σου
							</div>
							<div id="nullpwd">
								<span class="s_invalid">&nbsp;</span>Πρέπει να συμπληρώσεις τον κωδικό πρόσβασής σου
							</div>
						</div>
						<div class="next">
							<a href="" class="button">Επόμενο &raquo;</a>
						</div>
						<div class="barfade">
	                        <div class="leftbar"></div>
	                        <div class="rightbar"></div>
	                    </div>
						<p class="imtos">
							Δεν αποθηκεύουμε το email και τον κωδικό πρόσβασης σου και δε γίνεται καμία ενέργεια χωρίς την έγκρισή σου
						</p>
					</div>
				</div><?php
				$page->AttachInlineScript( 'Im.ImOnLoad();' );
			}
			else {
				?>Για να χρησιμοποιήσεις αυτή τη δυνατότητα πρέπει να έχεις συνδεθεί στο zino<?php
			}
		}
	}
?>