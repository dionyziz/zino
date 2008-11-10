<?php
	$openinviter_settings=array(
		"username"=>"pagiopagio",
		"private_key"=>"ac2b055129a89ee0a07587aac63dc98b",
		"cookie_path"=>"/tmp",
		"message_body"=>"You are invited to pagio.gr",
		"message_subject"=>" is inviting you to pagio.gr",
		"filter_emails"=>TRUE, //Tell OpenInviter whether to compare the emails with its blacklist or not. Default value: TRUE (compare enabled).
		"transport"=>"curl", //Replace "curl" with "wget" if you would like to use wget instead
		"local_debug"=>"on_error", //Available options: on_error => log only requests containing errors; always => log all requests; false => don`t log anything
		"remote_debug"=>TRUE //When set to TRUE OpenInviter sends debug information to our servers. Set it to FALSE to disable this feature
	);
	?>