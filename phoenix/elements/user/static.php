<?php
	function ElementUserStatic( $theuser, $link = true, $bold = false ) {
		$boldstart = $boldend = $linkstart = $linkend = "";
		
        $link = $link && $theuser->Exists();
        
		if ( $link ) {
			$newchanges = false;
			if ( $theuser->LPE() != "0000-00-00" ) {
				$nowdate = strtotime( NowDate() );
				$olddate = strtotime( $theuser->LPE() );
				$diff = $nowdate - $olddate;
				if ( $diff < 86400 ) {
					// one day
					$newchanges = true;
				}
			}
		
			if ( $theuser->Rights() <= 20 ) {
				// user
				$cssclass = "user_user";
			}
			else if ( $theuser->Rights() <= 30 ) {
				// journalist
				$cssclass = "journalist";
			}
			else if ( $theuser->Rights() <= 50 ){
				// programmer
				$cssclass = "operator";
			}
			else {
				// superuser
				$cssclass = "developer";
			}
			$xstyle = "";
			if ( $newchanges ) {
				$xstyle = " style=\"border-bottom: 1px dashed gray;\"";
			}
			$id = $theuser->Id();
            $linkstart = "<a href=\"user/" . $theuser->Username() . "\" class=\"$cssclass\"$xstyle>";
            $linkend = "</a>";
		}
		if ( $bold ) {
			$boldstart = "<b>";
			$boldend = "</b>";
		}
		$uname = "$linkstart$boldstart" . $theuser->Username() . "$boldend$linkend";
		
		echo $uname;
	}
?>