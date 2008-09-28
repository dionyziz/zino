<?php
	function AddRICon( $id ) {
		global $ricons;
		global $user;
		global $db;
		
		$id = myescape( $id );
		
		$sql = "SELECT `ricon_id` FROM `$ricons` 
			WHERE `ricon_imageid`='$id' LIMIT 1;";
		$res = $db->Query( $sql );
	
		if ( !$res->Results() ) {
			$sql = "INSERT INTO `$ricons`
					( `ricon_id` , `ricon_imageid` , `ricon_adminid` , `ricon_date` , `ricon_adminhost` )
					VALUES( '' ,      '$id' ,  '" . $user->Id() . "' , '" . NowDate() . "' , '" . UserIp() . "' ); ";
			$db->Query( $sql );
		}
	}
	
	function getAllRICons() {
		global $db;
		global $ricons;
		
		$sql = "SELECT * FROM `$ricons` ORDER BY `ricon_id` DESC;";	
		
		$res = $db->Query( $sql );
		$ret = $res->MakeArray();
		
		return $ret;
	}
	
	class RICon {
		var $mImageId;
		
		function ImageId() {
			return $this->mImageId;
		}
		function RIcon( $fetched_array ) {
			$this->mImageId = $fetched_array[ "ricon_imageid" ];
		}
	}
?>