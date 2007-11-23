<?php

	class Pageviewer {
		public function Get( $type, $keys ) {
			global $db;
			global $pageviews;
			
			if ( !is_array( $keys ) ) {
				$keys = array( $key );
			}

			if ( count( $keys ) == 0 ) {
				return array();
			}
			
			foreach ( $keys as $i => $key ) {
				$keys[ $i ] = myescape( $key );
			}
			
			$type = myescape( $type );
			
			$sql = "SELECT 
						`pageview_itemid`, `pageview_type`, COUNT(*) AS pageviews
					FROM 
						`$pageviews` 
					WHERE 
						`pageview_itemid` IN (" . implode( ", ", $keys ) . ")
						AND `pageview_type`='" . $type . "'
					GROUP BY
						`pageview_itemid`;";
			
			$res = $db->Query( $sql );
			$ret = array();
			while ( $row = $res->FetchArray() ) {
				$ret[ $row[ 'pageview_itemid' ] ] = $row[ 'pageviews' ]; // ret[ 255 ] = pageviews of item 255
			}
			return $ret;
		}
		public function Add( $type, $id, $userid = "" ) {
			global $db;
			global $pageviews;
			global $user;
			
			if ( empty( $userid ) ) { // if user id is not provided
				$userid = $user->Id(); // assume that the user is the one viewing the page now
			}
			
			$insert = array(
				'pageview_type' => $type,
				'pageview_itemid' => $id,
				'pageview_userid' => $userid,
				'pageview_date' => NowDate()
			);
			
			return $db->Insert( $insert , $pageviews, false, true )->Impact(); // Run the query and return whether insert was successful or not
		}
	}
	
	global $pageviewer;
	
	$pageviewer = New Pageviewer;
?>