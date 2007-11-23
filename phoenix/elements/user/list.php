<?php
	function ElementUserList( tInteger $offset ) {
		global $user;
		global $page;
		global $libs;
		global $water; 
		global $xc_settings;
        
		$libs->Load( 'user' );
		
		$offset = $offset->Get();
		if ( !ValidId( $offset ) ) {
			$offset = 1;
		}
        
		$page->SetTitle( 'Τα μέλη μας' );
		$page->AttachScript( 'js/friends.js' );
		$page->AttachScript( 'js/coala.js' );
		$page->AttachStyleSheet( 'css/userlist.css' );
		$water->Trace( 'offset: '.$offset );
		$userlist = ListAllUsersByRights( false , $offset , 50 );
		$water->Trace( 'number of userlist: '.count( $userlist ) );
		
		?><br /><br /><br /><br /><span class="heading">Τα μέλη μας</span>
		<div class="allusers">
			<ul style="list-style-type: none;"><?php
			foreach ( $userlist as $theuser ) {
				?>
				<div class="thisuser">
					<li onmouseover="g( 'toolbox_<?php
					echo $theuser->Id();
					?>' ).style.display = 'inline';" onmouseout="g( 'toolbox_<?php
					echo $theuser->Id();
					?>' ).style.display='none';"><?php
					
					Element( 'user/static', $theuser );
					
					?>&nbsp;<div id="toolbox_<?php
					echo $theuser->Id();
					?>" style="display: none;" ><?php
					
					$isfriend = $user->IsFriend( $theuser->Id() );
					if ( !$user->IsAnonymous() && $user->Id() != $theuser->Id() ) { 
						?><span id="friendadd"><?php
						if ( !$isfriend ) { 
							?><a href="" onclick="Friends.AddFriend(<?php
							echo $theuser->Id();
							?> );window.location.reload();return false;"><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/user_add.png" title="Προσθήκη στους φίλους μου" alt="Προσθήκη στους φίλους" width="16" height="16" /></a><?php
						}
						else { 
							?><a href="" onclick="Friends.DeleteFriend(<?php
							echo $theuser->Id();
							?>);window.location.reload();return false;"><img src="<?php
                            echo $xc_settings[ 'staticimagesurl' ];
                            ?>icons/user_delete.png" title="Διαγραφή από τους φίλους μου" alt="Διαγραφή από τους φίλους μου" width="16" height="16" /></a><?php
						}
						?></span><?php
					}
					if ( $user->CanModifyCategories() && ( $user->Rights() > $theuser->Rights() || $user->Id() == $theuser->Id() ) ) { 
						?>&nbsp;<a href="?p=useradmin&amp;id=<?php
						echo $theuser->Id();
						?>"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/group_edit.png" title="Επεξεργασία Δικαιωμάτων" alt="Επεξεργασία Δικαιωμάτων" width="16" height="16" /></a><?php
					}
					if ( $user->IsSysOp() && $user->Id() != $theuser->Id() ) {
						?>&nbsp;<a href="?p=su&amp;name=<?php
						echo $theuser->Username();
						?>"><img src="<?php
                        echo $xc_settings[ 'staticimagesurl' ];
                        ?>icons/user_go.png" title="Είσοδος ως <?php
						echo $theuser->Username();
						?>" alt="Είσοδος ως <?php
						echo $theuser->Username();
						?>" /></a><?php
					}
					?></div></li>
				</div><?php
			}
			?></ul>
		</div><?php
		$allusers = CountUsers();
		Element( 'pagify' , $offset , 'userlist' , $allusers , 50 );
	}
?>
