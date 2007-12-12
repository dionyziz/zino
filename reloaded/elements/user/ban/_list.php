<?php
	function ElementUserBanList() {
		global $page;
		global $user;
		global $libs;
		global $xc_settings;
		
		$libs->Load( 'ipban' );
		$page->SetTitle( "Αποκλεισμένα IP" );
		$page->AttachStylesheet( "css/banlist.css" );
		
		if ( !$user->CanModifyCategories() ) {
            return Redirect();
    	}

		$banlist = IPBan_List();
		?> 
		<br/><br/><br/>
		<br/><br/>
		<table class="bans">			
				<tr class="titles">
					<th></th>
					<th>Αποκλεισμένο IP</th>
					<th>Δημιουργήθηκε</th>
					<th>Λήξη</th>
					<th>από το Χρήστη</th>
				</tr>
				<tr>
					<td colspan="2">
						<form action="do/ip/ban" method="post">
						<input type="submit" class="add" value="" style="background-image: 					
						url('<?php
						echo $xc_settings[ 'staticimagesurl' ];
						?>icons/add.png');" />
						<input type="text" name="ip" />
						</form>
					</td>
					<td>Τώρα</td>
					<td>Σε μια βδομάδα</td>
					<td><?php
						Element( "user/static" , $user )
						?></td>
				</tr><?php
					
				$bgcolor = true;
				
				foreach( $banlist as $ban ) {
					
					$today 		= NowDate();
					$date	    = $ban->Date;
					$expiration = $ban->ExpireDate;
					
					if ( $bgcolor == true ){
						?><tr class="color"><?php
						$bgcolor = false;
					}
					else{
						?><tr><?php
						$bgcolor = true;
					}
					?>
					<td><form action="do/ip/unban" method="post">
						<input type="hidden" name="id" value="<?php
						echo $ban->Id;
						?>" /><input type="submit" class="delete" value="" style="background-image: 					
						url('<?php
						echo $xc_settings[ 'staticimagesurl' ];
						?>icons/delete.png');" />
						</form>
					</td>
					<td><?php
					
					echo $ban->Ip; 
					?></td>
					<td>πριν <?php
					echo dateDiff( $date, $today ); 
					?></td>
					<td><?php

					if ( $today < $expiration ) {
						?>σε <?php
						echo dateDiff( $today, $expiration );
					}
					else {
						?>πριν <?php					
						echo dateDiff( $expiration, $today );
					}
					?></td>
					<td><?php 
					Element( "user/static" , $ban->SysOp );
					?></td>
				</tr><?php
				}
		?></table><?php
	}
?>
