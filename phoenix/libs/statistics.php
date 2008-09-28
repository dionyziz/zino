<?php
	function Statistics_Get( $table_name, $days_passed = 30 ) {
		global $db;

		switch ( $table_name ) {
			case "shoutbox":
				$date_field = "shout_created";
				break;
			case "users":
				$date_field = "user_created";
				break;
			case "polls":
				$date_field = "poll_created";
				break;
			case "comments":
				$date_field = "comment_created";
				break;
			case "images":
				$date_field = "image_created";
				break;
			case "journals":
				$date_field = "journal_created";
				break;
			case "albums":
				$date_field = "album_created";
				break;
			default:
				return;
		}

		$query = $db->Prepare( "SELECT DATE(" . $date_field . ") AS day,COUNT(*) AS count  FROM :$table_name WHERE " . $date_field . ">NOW()-INTERVAL :days_before DAY GROUP BY day ORDER BY day ASC" );
		$query->BindTable( $table_name );
		$query->Bind( 'days_before', $days_passed );
		$res = $query->Execute();
		
		$array = $res->MakeArray();		

		return $array;
	}
?>
