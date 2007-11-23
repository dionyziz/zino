<?php

	function ElementUserAdmin( tInteger $id ) {
		global $user;
		global $page;
		
        $uid = $id->Get();
        
		if ( !$user->CanModifyCategories() ) {
			Element( '404' );
		}
		else {
			$theuser = New User( $uid );
			$page->SetTitle( "Διαχείριση Χρήστη " . $theuser->UserName() );
			
			?><div class="userinfo"><?php
			
			Element( 'user/display', $theuser );
			?><br /><small>Δικαιώματα: <?php
			echo $theuser->Rank();
			?></small></div>
			<div style="clear:both;"></div><br />
			<form action="do/user/setprivileges" method="post">
			<input type="hidden" name="id" value="<?php
			echo $uid 
			?>" />
			Νέα Δικαιώματα: <select name="rights"><?php
			
			$startfrom = 0;
			$endat = $user->Rights();
			for ( $i = $startfrom ; $i <= $endat ; $i += 10 ) {
				if( $i != 20 ) {
					?><option value="<?php
					echo $i;
					?>"<?php
					if ( $i == $theuser->Rights() ) {
						?> selected="selected"<?php
					}
					?>><?php
					echo RankToText( $i );
					?> (<?php
                    echo $i;
                    ?>)</option><?php
				}
			}
			?></select><br /><input type="submit" value="Αποθήκευση" /></form><?php
		}
	}
?>