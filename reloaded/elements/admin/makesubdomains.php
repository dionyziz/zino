<?php
	function ElementAdminMakesubdomains( ) {
		global $user;
        global $db;
		
		if ( $user->Username() != 'makis' ) {
			?>Δεν έχετε πρόσβαση<?php
			return 0;
		}
		
		$sql = "SELECT 
					`user_id` , `user_name` 
				FROM 
					`merlin_users` 
				WHERE 
					`user_subdomain` = ''
				LIMIT 10 ;";
		
        $res = $db->Query( $sql );
        
        $rows = array();
		$subdomains = array();
		?><h2>Subdomains</h2>
<table><?php
        while ( $row = $res->FetchArray() ) {
			$subdomains[ $row[ 'user_id' ] ] = User_DeriveSubdomain( $row[ 'user_name' ] );
            ?><tr><td><?php echo htmlentities( $row[ 'user_id' ] ); ?>: <?php echo htmlentities( $row[ 'user_name' ] ); ?></td><td><?php echo $subdomains[ $row[ 'user_id' ] ]; ?></td></tr><?php
        }
		?></table><br />--<?php
		
	}
/* -- samples --
"UPDATE `ccbeta`.`merlin_users` SET `user_subdomain` = '$subdomain' WHERE `merlin_users`.`user_id` =$userid LIMIT 1 ;"

"SELECT `user_id` , `user_name` 
FROM `merlin_users` 
WHERE `user_subdomain` = ''
LIMIT 30 ; "
*/
?>