<?php
	function ElementAdminMakesubdomains( tInteger $update, tInteger $limit ) {
		global $user;
        global $db;
		global $users;
		
		$update = $update->Get();
		$limit = $limit->Get();
		if ( $limit < 10 || $limit > 300 ) {
			$limit = 30;
		}
		
		if ( $user->Username() != 'makis' ) {
			?>Δεν έχετε πρόσβαση<?php
			return 0;
		}
		
		$sql = "SELECT 
					`user_id` , `user_name` 
				FROM 
					`$users` 
				WHERE 
					`user_subdomain` = ''
				LIMIT 30 ;";
		
        $res = $db->Query( $sql );
        
        $rows = array();
		$subdomains = array();
		?><h2>Subdomains</h2>
		<table><?php
        while ( $row = $res->FetchArray() ) {
			$subdomains[ $row[ 'user_id' ] ] = User_DeriveSubdomain( $row[ 'user_name' ] );
            ?><tr><td><?php echo htmlspecialchars( $row[ 'user_id' ] ); ?>: <?php echo htmlspecialchars( $row[ 'user_name' ] ); ?></td><?php
			?><td><?php echo $subdomains[ $row[ 'user_id' ] ]; ?></td><?php 
			?><td><?php echo 
				"UPDATE 
					`$users` 
				SET 
					`user_subdomain` = '". myescape( $subdomains[ $row[ 'user_id' ] ] ) . "' 
				WHERE 
					`user_id` =" . $row[ 'user_id' ] . " 
				LIMIT 1 ;"; ?></td></tr>
<?php
        }
		?></table><br /><?php
		// CHECKING FOR DUPLICATES
		
		// 1) in the array we've already got
		$diff = array_diff_key( $subdomains, array_unique( $subdomains ) );
		if( count( $diff ) > 0 ) {
			?>Too bad.<br /><?php
			foreach( $diff as $key => $val ) {
				echo "User " . $key . ": " . htmlspecialchars( $val ) . " (of this list) conflicts with one of the above.";
			}
			return 2;
		}
		// 2) in the rest of the database
		if ( count( $subdomains ) > 1 ) {
			$list = implode( "', '", array_values( $subdomains ) ); 
			//echo "IN ( '" . htmlspecialchars( $list ) . "' )";

			$sql = "SELECT 
						`user_id` , `user_name` , `user_subdomain`
					FROM 
						`$users` 
					WHERE 
						`user_subdomain` IN ( '$list' ) 
					LIMIT 1;";
			$sqlresult = $db->Query( $sql );
			if ( $sqlresult->Results() ) { // If there is someone in the list with the same subdomain
				$conflict = $sqlresult->FetchArray();
				echo "Too bad. At least user " . $conflict[ 'user_id' ] . ": " . htmlspecialchars( $conflict[ 'user_name' ] ) . " with subdomain " . $conflict[ 'user_subdomain' ] . " conflicts with one of the above list.";
				return 2;
			}
		}
		?><br />--<?php
		
		//If we've reached that far, everything is fine.
		//Executing UPDATE queries
		if ( $update != 1 ) {
			return 0;
		}
		foreach( $subdomains as $uid => $val ) {
			$sql = "UPDATE 
						`$users` 
					SET 
						`user_subdomain` = '". myescape( $val ) . "' 
					WHERE 
						`user_id` =" . $uid . " 
					LIMIT 1 ;";
			$db->Query( $sql );
			?><br />
Updated key <?php echo $uid; ?>!<?php
		}
		
		?><br />--Done--<?php
		return 0;
	}
/* -- samples --
"UPDATE `ccbeta`.`merlin_users` SET `user_subdomain` = '$subdomain' WHERE `merlin_users`.`user_id` =$userid LIMIT 1 ;"

"SELECT `user_id` , `user_name` 
FROM `merlin_users` 
WHERE `user_subdomain` = ''
LIMIT 30 ; "
*/
?>