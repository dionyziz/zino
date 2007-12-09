<?php
	function UnitNotifyMarkAsRead( tInteger $notifyid , tBoolean $navigate , tCoalaPointer $hidewhat ) {
		global $libs;
		global $user;
		
        $notifyid = $notifyid->Get();
        $navigate = $navigate->Get();
        
		$libs->Load( 'notify' );
		$libs->Load( 'comment' );
		
		$notif = New Notify( $notifyid );
		
		if ( $notif->ToUserid() == $user->Id() ) {
			$typeid = $notif->Typeid();
			$comm = $notif->Page();
            if ( $navigate ) {
				$notif->Read();
    			?>document.location = "<?php
    			switch ( $typeid ) {
    				case 0:
    					$artcl = $comm->Page();
    					?>index.php?p=story&id=<?php
    					echo $artcl->Id();
    					?>#comment_<?php
    					echo $notif->Itemid();
    					break;
    				case 1: // Reply to Comment
    				case 4: // Profile Comment
    					?>user/<?php
    					echo $comm->Page()->Username();
    					?>#comment_<?php
    					echo $notif->Itemid();
    					break;
    				case 2:
                    case 5:
    					$artcl = $comm->Page();
    					?>index.php?p=photo&id=<?php
    					echo $artcl->Id();
    					?>#comment_<?php
    					echo $notif->Itemid();
    					break;
					case 3:
						$artcl = $comm->Page();
						?>index.php?p=poll&id=<?php
						echo $artcl->Id();
						?>#comment_<?php
						echo $notif->Itemid();
						break;
    				case 128:
    					?>user/<?php
    					echo $comm->Username();
    					?>?viewfriends=yes<?php
    					break;
    			}
        		?>";<?php
            }
            if ( $hidewhat->Exists() ) {
				$notif->Delete();
				?>linode = <?php
				echo $hidewhat;
				?>;
				liparent = linode.parentNode;
				if ( linode.className.indexOf( "next" ) == -1 ) {
					linext = linode.nextSibling.nextSibling;
					if ( linext ) {
						linext.className = "<?php
						if ( $notif->Typeid() <=3 ) {
							?>comment<?php
						}
						else {
							?>friend<?php
						}
						?>";
					}
				}
				if ( liparent.getElementsByTagName( 'li' ).length == 1 ) {
					divnotify = document.getElementById( 'notify' );
					divnotify.parentNode.removeChild( divnotify );
				}
				else {
					liparent.removeChild( linode );
				}<?php
            }
		}
	}
?>
