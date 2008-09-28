<?php
    echo 'Please edit this script in order to use it: remove the exit statement.';
    
    exit();
    
    include "../../header.php";
    
	/*
	Developers: Izual, Dionyziz
		this script will run once(i hope) and transfer all media from the database to the images folder
		the general type for a specific media will be /images/userid/mediaid
		where each user has a folder and each user's folder contains some media
	*/
	function images_to_harddisk() {
		global $images;
		
		$query = "SELECT `id` ,`userid` , `image` FROM `$images`;";
		$sqlr = mysql_query( $query );
		$num_rows = mysql_num_rows( $sqlr );
		for( $i = 0; $i < $num_rows; ++$i ) {
    		$thisimage = mysql_fetch_array( $sqlr );
			$imgid = $thisimage[ "id" ];
			$userid = $thisimage[ "userid" ];
            if ($userid > 0) {
    			$binary = base64_decode( $thisimage[ "image" ] );
    			$folder = "/home/virtual/excalibur.qlabs.gr/httpdocs/resources/" . $userid;
    			if ( !file_exists( $folder ) ) {
    				mkdir( $folder );
    			}
    			$file = $folder."/".$imgid;
                echo $file;
                ?>... <?php
    			$fp = fopen( $file , "w" );
    			fwrite( $fp , $binary );
    			fclose( $fp );
                chmod( $file , 0644 );
                echo round(($i + 1) / $num_rows * 100);
                ?>% completed...<br /><?php
                flush();
            }
		}
	}
    images_to_harddisk();
?>