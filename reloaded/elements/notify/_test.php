<?php
	function ElementNotifyTest() {
		global $user;
		global $libs;
		
		$libs->Load( 'notify' );
		$libs->Load( 'comment' );
		
		$notifications = Notify_GetByUser( $user->Id() , 1 , 10 );
		foreach ( $notifications as $notif ) {
			/*
			?>Id: <?php
			echo $notif->Id();
			?><br /> Creation date: <?php
			echo $notif->SubmitDate();
			?><br /> Fromuserid: <?php
			echo $notif->FromUserid();
			?><br /> Touserid: <?php
			echo $notif->ToUserid();
			?><br /> Itemid: <?php
			echo $notif->Itemid();
			?><br /> Typeid: <?php
			echo $notif->Typeid();
			?><br /> Delid: <?php
			echo $notif->Delid();
			?><br /> Exists?: <?php
			echo $notif->Exists();
			?><br /><br /><?php
			*/
			$userv = $notif->UserFrom();
			$comm = $notif->Page();
			$artcl = $comm->Page();
			$itemid = $notif->Itemid();
			switch ( $notif->Typeid() ) {
				case 0:
					?>Σχόλιο από τον <?php
					Element( "user/static" , $userv , true , false );
					?> στο άρθρο <a href="index.php?p=story&amp;id=<?php
					echo $artcl->Id();
					?>#comment_<?php
					echo $itemid;
					?>"><?php
					echo $artcl->Title();
					?></a><?php
					break;
				case 1:
					?>Σχόλιο από τον <?php
					Element( "user/static" , $userv , true , false );
					?> στο προφίλ <a href="user/<?php
					echo $artcl->Username();
					?>#comment_<?php
					echo $itemid;
					?>">σου</a><?php
					break;
				case 2:
					?>Σχόλιο από τον <?php
					Element( "user/static" , $userv , true , false );
					?> στη φωτογραφία <a href="index.php?p=photo&amp;id=<?php
					echo $artcl->Id();
					?>#comment_<?php
					echo $itemid;
					?>"><?php
					echo $artcl->Name();
					?></a><?php
					break;
				case 128:
					?>Ο <?php
					Element( "user/static" , $userv , true , false );
					?> σας πρόσθεσε στους φίλους του<?php
					break;
			}
			?><br /><br /><?php
		}
	}
?>