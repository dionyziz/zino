<?php

	function ElementUserOptionsIcon( $sizeok, $extok ) {
		global $user;
		global $xc_settings;
		
		if ( $user->Rights() >= $xc_settings[ "allowuploads" ] ) {
			?><span class="headings" onclick="SetCat.activate_category( '2' );"><img id="setimg2" src="<?php
			echo $xc_settings[ 'staticimagesurl' ];
			?>icons/settings-collapsed.png" /> Εικονίδιο</span>
			
			<a href="?p=faqc&amp;id=18" style="display: inline;">
				<img src="<?php
					echo $xc_settings[ 'staticimagesurl' ];
				?>icons/help.png" alt="Πληροφορίες για το εικονίδιο χρήστη" style="width: 16px; height: 16px; opacity: 0.5;" onmouseover="this.style.opacity=1;g( 'commenthelp' ).style.visibility='visible';" onmouseout="this.style.opacity=0.5;g( 'commenthelp' ).style.visibility='hidden';" />
			</a><br /><br />
			
			<div id="cat2" class="avatar"><?php
				$usericonid = $user->Icon();
				if ( $usericonid == 0 ) { ?>
					<img src="http://static.chit-chat.gr/images/<?php
					echo $xc_settings[ 'staticimagesurl' ];
					?>anonymous.jpg" alt="<?php
					echo $user->Username();
					?>" title="<?php
					echo $user->Username();
					?>" style="border:1px solid #306faf;padding:1px" /><br /><?php
				}
				else { 
					$style = 'border:1px solid #306faf;padding:1px';
					Element( 'image' , $user->Icon() , 50 , 50 , '' , $style  , $user->Username() , $user->Username() );
				} 
				?><br />
				
				<span class="minitip">(αν θέλεις να αλλάξεις το εικονίδιό σου, απλώς επέλεξε ένα νέο εικονίδιο και αποθήκευσε)</span><br />
				<input type="file" name="usericon" /><?php
				if ( !$sizeok ) {
					?>&nbsp;&nbsp;&nbsp;Το εικονίδιο θα μετατραπεί σε μέγεθος 50 x 50 pixels<?php
				}
				else if ( !$extok ) {
					?>&nbsp;&nbsp;&nbsp;Το εικονίδιο πρέπει να είναι σε μορφή .jpg ή .png<?php
				}
				?><br />
				<br /><br />
			</div><?php
		}
	}
	
?>