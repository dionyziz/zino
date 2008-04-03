<?php
	function UnitUserJoin( tString $username , tString $password , tString $email ) {
		$username = $username->Get();
		$password = $password->Get();
		$email = $email->Get();
		?>alert( 'username: <?php 
		echo $username;
		?>' );
		alert( 'password: <?php
		echo $password;
		?>' );
		alert( 'email: <?php
		echo $email;
		?>' );<?php
		$newuser = new User();
	}
?>
