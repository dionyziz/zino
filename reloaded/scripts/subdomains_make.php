<?php
	set_include_path( '../:./' );
	
	global $users;
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all

	header( 'Content-Type: text/html; charset=iso-8859-7' );

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
	<head>
		<title>Subdomains</title>
	</head>
	<body style="text-align:left;padding-left:10px;">
	<?php 
		$update = $_GET[ 'update' ];
		$limit = $_GET[ 'limit' ];
		if ( $limit < 10 || $limit > 500 ) {
			$limit = 30;
		}
		$limit = addslashes( $limit );
		
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
				LIMIT $limit ;";
		
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
		
	?>
	</body>
</html>
