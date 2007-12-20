<?php
	set_include_path( '../:./' );
	
	require '../libs/rabbit/rabbit.php';
	
    Rabbit_Construct();
		
	$water->Enable(); // on for all
	$libs->Load( 'user' );

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
		
        $res = mysql_query( $sql );
        
        $rows = array();
		$subdomains = array();
		$zerolengths = array();
		?><h2>Subdomains</h2>
		<table><?php
        while ( $row = mysql_fetch_array( $res ) ) {
			$subdomain = User_DeriveSubdomain( $row[ 'user_name' ] ) or User_DeriveSubdomain( 'u' . $row[ 'user_name' ] );
			if ( $subdomain != 'u' ) {
				$subdomains[ $row[ 'user_id' ] ] = $subdomain;
	            ?><tr><td><?php echo htmlspecialchars( $row[ 'user_id' ] ); ?>: <?php echo htmlspecialchars( $row[ 'user_name' ] ); ?></td><?php
				?><td><?php echo $subdomains[ $row[ 'user_id' ] ]; ?></td><?php 
				?><td><?php echo 
					"UPDATE 
						`$users` 
					SET 
						`user_subdomain` = '". addslashes( $subdomains[ $row[ 'user_id' ] ] ) . "' 
					WHERE 
						`user_id` =" . $row[ 'user_id' ] . " 
					LIMIT 1 ;"; ?></td></tr>
<?php
			}
			else {
				$zerolengths[ $row[ 'user_id' ] ] = $row[ 'user_name' ];
			}
		}
		?></table><br /><?php
		//WARNINGS
		//Zero length subdomains:
		foreach( $zerolengths as $uid => $uname ) {
			?>-WARNING- User <?php echo $uid; ?>: <?php echo $uname; ?> produces a zero-length subdomain!<br />
<?php
		}
		// CHECKING FOR DUPLICATES
		
		// 1) in the array we've already got
		$unique_subdomains = array_unique( $subdomains );
		$diff = array_diff_key( $subdomains, $unique_subdomains );
		if( count( $diff ) > 0 ) {
			?>Too bad.<br /><?php
			foreach( $diff as $key => $val ) {
				echo "User " . $key . ": " . htmlspecialchars( $val ) . " (of this list) conflicts with one of the above.<br />\n";
				$uid = array_keys( $unique_subdomains, $val );
				foreach( $uid as $k ) {
					echo " -$k <br />\n";
					unset( $unique_subdomains[ $k ] );
				}
			}
			//return 2;
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
			$sqlresult = mysql_query( $sql );
			if ( mysql_num_rows( $sqlresult ) ) { // If there is someone in the list with the same subdomain
				$conflict = mysql_fetch_array( $sqlresult );
				echo "Too bad. At least user " . $conflict[ 'user_id' ] . ": " . htmlspecialchars( $conflict[ 'user_name' ] ) . " with subdomain " . $conflict[ 'user_subdomain' ] . " conflicts with one of the above list.";
				$uid = array_keys( $unique_subdomains, $conflict[ 'user_subdomain' ] );
				foreach( $uid as $k ) {
					echo " -$k <br />\n";
					unset( $unique_subdomains[ $k ] );
				}
				//return 2;
			}
		}
		?><br />--<?php
		//print_r( $unique_subdomains );
		
		//If we've reached that far, everything is fine.
		//Executing UPDATE queries
		if ( $update != 1 ) {
			return 0;
		}
		foreach( $unique_subdomains as $uid => $val ) {
			$sql = "UPDATE 
						`$users` 
					SET 
						`user_subdomain` = '". myescape( $val ) . "' 
					WHERE 
						`user_id` =" . $uid . " 
					LIMIT 1 ;";
			$res2 = mysql_query( $sql );
			if ( $res2 ) {
				?><br />
Updated key <?php echo $uid; ?><?php
			}
			else {
				?><br />
<?php echo $uid; ?> FAILED!<?php
			}
		}
		
		?><br />--Done--<?php
		
	?>
	</body>
</html>
