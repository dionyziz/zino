<?php
	function UnitErrorhandler( tText $msg, tText $url, tInteger $linenumber ){
		global $user;
		$to = "thsourg@gmail.com";
		if( $user->Exists() ){
			$subject = "[ZINO][ERRORS]Error from user " . $user->Name;
		}
		else{
			$subject = "[ZINO][ERRORS]Error from a user";
		}
		$text = " Error on global.js, line $linenumber.
Page: $url.
Message: $msg.";
		mail( $to, $subject, $text );
	}
?>