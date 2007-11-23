<?php

	function getNewChatMessages( $lastid ) {
		global $chats;
		global $users;
		global $db;
        
		if ( !is_numeric( $lastid ) ) {
			return array();
		}
		
		$sql = "SELECT 
					`chat_id`,
					`chat_message`,
					`user_id`, 
					`user_name`,
					`user_rights`,
					`user_lastprofedit`,
					`user_gender`,
					`user_icon`
				FROM
					`$chats` LEFT JOIN `$users` ON
						`chat_userid` = `user_id`
				WHERE
					`chat_id` > '" . $lastid . "'
				ORDER BY
					`chat_id` DESC
				LIMIT 15;";
		
		$res = $db->Query( $sql );
		
		$ret = $res->MakeArray();
		krsort( $ret );
		
		return $ret;
	}

	function CCAnnounce( $news ) {
		AddChat( $news , 0 , '127.0.0.1' );
	}
	
	function AddChat( $message , $userid = false , $ip = false ) {
		global $chats;
		global $user;
		global $db;
        
		if ( $userid === false ) {
			$userid = $user->Id();
		}
		if ( $ip === false ) {
			$ip = UserIp();
		}
		
		$insert = array(
			'chat_id' => '',
			'chat_message' => $message,
			'chat_userid' => $userid,
			'chat_date' => NowDate(),
			'chat_userip' => $ip
		);
		$db->Insert( $insert, $chats );
	}
	
	function UsersInChat() {
		global $users;
        global $db;
        
		$sql = "SELECT 
					`user_id` AS user_id,
					`user_name`,
					`user_rights`,
					`user_icon`,
					NOW() - `user_lastprofedit` > 86400 AS recentchanges
				FROM
					`$users`
				WHERE 
					`user_inchat` + INTERVAL 2 MINUTE > NOW();";
	
		$res = $db->Query( $sql );
		
		return $res->MakeArray();
	}
	
	function ChatHistory() {
		global $chats;
		global $users;
		global $db;
        
		$sql = "SELECT 
				`chat_message`,
				(`chat_date` + INTERVAL 2 HOUR) AS `chat_cutedate`,
				`user_id`,
				`user_name`,
				`user_rights`,
				`user_lastprofedit`,
				`user_gender`
			FROM
				`$chats` LEFT JOIN `$users`
			ON
				`chat_userid`=`user_id`
			ORDER BY
				`chat_id` DESC
			LIMIT 30;";
			
		$res = $db->Query( $sql );
		$ret = $res->MakeArray();
		
		return $ret;
	}
	
	function ChatLastId() {
		global $chats;
		global $db;
		
		$sql = "SELECT
					`chat_id`
				FROM
					`$chats`
				ORDER BY
					`chat_id` DESC
				LIMIT 1;";
		
		$res = $db->Query( $sql );
		if ( !$res->Results() ) {
			return 0;
		} // else
		
		$row = $res->FetchArray();
		return $row[ 'chat_id' ];
	}
	
	class Chat {
		var $mId;
		var $mMsg;
		var $mUserId;
		var $mIp;
		var $mDate;
		
		function Id() {
			return $this->mId;
		}
		function Date() {
			return $this->mDate;
		}
		function Ip() {
			return $this->mIp;
		}
		function UserId() {
			return $this->mUserId;
		}
		function Message() {
			return $this->mMsg;
		}
		
		function Chat( $fetched_array ) {
			$this->mId = $fetched_array[ "chat_id" ];
			$this->mMsg = $fetched_array[ "chat_message" ];
			$this->mUserId = $fetched_array[ "chat_userid" ];
			$this->mDate = $fetched_array[ "chat_date" ];
			$this->mIp = $fetched_array[ "chat_userip" ];
		}
	
	}
?>
