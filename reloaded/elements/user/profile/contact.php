<?php
    // Shows any instant messaging accounts the user has. $classk is a variable used for changing the background of the line where the account appears,so that first time msn
    // appears with white background and at the next line,yim appears with grey background
	function ElementUserProfileContact( $theuser ) {
        global $xc_settings;
		global $user;
		global $page;
        
		if ( $theuser->Id() == $user->Id() || $theuser->MSN() || $theuser->YIM() || $theuser->AIM() || $theuser->ICQ() || $theuser->GTalk() || $theuser->Skype() ) {
			
			if ( $user->Id() == $theuser->Id() && $user->Rights() >= $xc_settings[ 'readonly' ] ) {
				ob_start();
				?>ProfileOptions.Init( g( "user_profile_contact" ) );<?php
				$page->AttachInlineScript( ob_get_clean() );
			}
			
			?><div class="contact">
				<h4>επικοινωνία <a href="faq/contact_information" style="margin-left: 3px;">?</a></h4>
				<ul id="user_profile_contact"><?php
					$classk = false;
					if ( $theuser->MSN() || $user->Id() == $theuser->Id() ) { 
						if ( $classk ) {
							$classk = false;
						}
						else {
							$classk = true;
						} ?>
					<li><dl>
						<dt><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>messenger/msn.png" alt="MSN" title="MSN Messenger" /></dt>
						<dd id="user_options_msn"><?php
							echo htmlspecialchars( $theuser->MSN() );
						?></dd>
					</dl></li><?php
					}
					if ( $theuser->YIM() || $user->Id() == $theuser->Id() ) { 
						if ( $classk ) {
							$class = ' class="k"';
							$classk = false;
						}
						else {
							$class = '';
							$classk = true;
						}
						?><li><dl<?php 
							echo $class; 
							?>>
							<dt><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>messenger/yahoo.png" alt="Yahoo" title="Yahoo! Messenger" /></dt>
							<dd id="user_options_yim"><?php
								echo htmlspecialchars( $theuser->YIM() );
							?></dd>
						</dl></li><?php
					}
					if ( $theuser->AIM() || $user->Id() == $theuser->Id() ) { 
						if ( $classk ) {
							$class = ' class="k"';
							$classk = false;
						}
						else {
							$class = '';
							$classk = true;
						}
						?><li><dl<?php 
                            echo $class; 
                            ?>>
							<dt><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>messenger/aim.png" alt="AIM" title="AIM" /></dt>
							<dd id="user_options_aim"><?php	
								echo htmlspecialchars( $theuser->AIM() );
							?></dd>
						</dl></li><?php
					}
					if ( $theuser->Skype() || $user->Id() == $theuser->Id() ) { 
						if ( $classk ) {
							$class = ' class="k"';
							$classk = false;
						}
						else {
							$class = '';
							$classk = true;
						}
						?><li><dl<?php 
                            echo $class; 
                            ?>>
							<dt><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>messenger/skype.png" alt="Skype" title="Skype" /></dt>
							<dd id="user_options_skype"><?php	
								echo htmlspecialchars( $theuser->Skype() );
							?></dd>
						</dl></li><?php
					}
					if ( $theuser->ICQ () || $user->Id() == $theuser->Id() ) { 
						if ( $classk ) {
							$class = ' class="k"';
							$classk = false;
						}
						else {
							$class = '';
							$classk = true;
						} 
						?><li><dl<?php 
							echo $class; 
							?>>
							<dt><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>messenger/icq.png" alt="ICQ" title="ICQ" /></dt>
							<dd id="user_options_icq"><?php
								echo htmlspecialchars( $theuser->ICQ() );
							?></dd>
						</dl></li><?php
					}
					if ( $theuser->GTalk() || $user->Id() == $theuser->Id() ) { 
						if ( $classk ) {
							$class = ' class="k"';
							$classk = false;
						}
						else {
							$class = '';
							$classk = true;
						} 
						?><li><dl<?php 
							echo $class; 
							?>>
							<dt><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>messenger/gtalk.png" alt="gtalk" title="Google Talk" /></dt>
							<dd id="user_options_gtalk"><?php
                                echo htmlspecialchars( $theuser->Gtalk() );
							?></dd>
						</dl></li><?php
					} 
				?></ul>
			</div><?php
		}
	}
?>
