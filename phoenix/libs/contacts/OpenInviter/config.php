<?php
	$openinviter_settings=array(
		"username"=>"pagio91",
		"private_key"=>"f9978db6941ecf9294be013f50261e7f",
		"cookie_path"=>'/tmp',
		"message_body"=>"You are invited to a.com", // a.com is the website on your account. If wrong, please update your account at OpenInviter.com
		"message_subject"=>" is inviting you to a.com", // a.com is the website on your account. If wrong, please update your account at OpenInviter.com
		"transport"=>"wget", //Replace "curl" with "wget" if you would like to use wget instead
		"local_debug"=>"on_error", //Available options: on_error => log only requests containing errors; always => log all requests; false => don`t log anything
		"remote_debug"=>FALSE //When set to TRUE OpenInviter sends debug information to our servers. Set it to FALSE to disable this feature
	);
	?>
