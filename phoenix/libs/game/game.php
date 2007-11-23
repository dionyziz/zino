<?php

	class Game {
		private $mGameId;
		private $mGameName;
		private $mMaxPlayers;
		private $mMinPlayers;
		
		public function MinPlayers() {
			return $this->mMinPlayers;
		}
		public function MaxPlayers() {
			return $this->mMaxPlayers;
		}
		public function GameId() {
			return $this->mGameId;
		}
		public function GameName() {
			return $this->mGameName;
		}
		public function GameExists() {
			return $this->mGameId > 0;
		}
		public function Game( $construct ) {
			global $db;
			global $water;
			global $game_list;
			
			$this->mGameId = 0;
			if ( is_numeric( $construct ) ) {
				$where = "`game_id` = '$construct'";
			}
			else if ( is_string( $construct ) ) {
				$construct = myescape( $construct );
				$where = "`game_name` = '$construct'";
			}
			else if ( is_array( $construct ) ) {
				$row = $construct;
			}
			if ( !isset( $row ) ) {
				$sql = "SELECT
							`game_id`, `game_name`, `game_maxplayers`, `game_minplayers`
						FROM
							`$game_list`
						WHERE
							$where
						LIMIT 1;";
				$res = $db->Query($sql);
				if ( !$res->Results() ) {
					$water->Notice( 'Invalid game' );
					return;
				}
				$row = $res->FetchArray();
			}
			$this->mGameId = isset( $row[ 'game_id' ] ) ? $row[ 'game_id' ] : 0;
			$this->mGameName = isset( $row[ 'game_name' ] ) ? $row[ 'game_name' ] : 'Game';
			$this->mMaxPlayers = isset( $row[ 'game_maxplayers' ] ) ? $row[ 'game_maxplayers' ] : 2;
			$this->mMinPlayers = isset( $row[ 'game_minplayers' ] ) ? $row[ 'game_minplayers' ] : 2;
		}
	}
	
	class Board extends Game {
		private $mBoardId;
		private $mBoardName;
		private $mTurn;
		private $mBoardInviteOnly;
		private $mBoardActive;
		private $mBoardStarted;
		private $mPlayers;
		private $mCreatorId;
		private $mCreator;

		public function InviteOnly() {
			return $this->mInviteOnly;
		}
		public function BoardStarted() {
			return $this->mBoardStarted;
		}
		public function BoardActive() {
			return $this->mBoardActive;
		}
		public function Turn() {
			return $this->mTurn;
		}
		public function BoardName() {
			return $this->mBoardName;
		}
		public function BoardId() {
			return $this->mBoardId;
		}
		public function Start() {
			global $user;
			global $db;
			global $board;
			
			if ( !$this->BoardStarted() ) {
				$players = $this->Players();
				foreach ( $players as $player ) {
					if ( !$player->IsReady() ) {
						return false;
					}
				}
				$sql = "UPDATE
							`$boards`
						SET
							`board_started`='yes'
						WHERE
							`board_id`='" . $this->BoardId() . "'";
				$db->Query( $sql );
				$this->mBoardStarted = true;
				return true;
			}
			return false;
		}
		public function Creator() {
			global $water;
			
			if ( $this->mCreator === false ) {
				$players = $this->Players();
				foreach ( $players as $userid => $player ) {
					if ( $userid == $this->mCreatorId ) {
						$this->mCreator = $player;
						break;
					}
				}
				if ( $this->mCreator === false ) {
					$water->Trace( 'Creator not found in players! Resetting creator.' );
					$this->ReplaceCreator();
				}
			}
			return $this->mCreator;
		}
		public function ReplaceCreator() {
			global $db;
			global $boards;
			global $boardplayers; 
			
			$sql = "SELECT 
						`bp_playerid`
					FROM 
						`$boardplayers`
					WHERE 
						`bp_boardid`='" . $this->mBoardId . "'
					ORDER BY
						`bp_joined` DESC
					LIMIT 1;";
			$res = $db->Query( $sql );
			if ( $res->NumRows > $this->MinPlayers() ) {
				$row = $res->FetchArray();
				$userid = $row[ "bp_playerid" ];
				$sql = "UPDATE
							`$boards`
						SET 
							`board_creatorid`='" . $userid . "'
						WHERE 
							`board_boardid`='" . $this->BoardId() . "'
						LIMIT 1;";
				$db->Query( $sql );
				$this->mCreatorId = $userid;
				$this->mCreator = false;
				$this->Creator();
			}
			else {
				$this->EndGame();
			}
		}
		public function EndGame() {
			global $db;
			global $boards;
			
			$sql = "UPDATE 
						`$boards`
					SET 
						`board_active` ='no'
					WHERE
						`bp_boardid` ='" . $this->BoardId() . "'
					LIMIT 1;";
			$db->Query( $sql );
			$this->mBoardActive = false;
		}
		public function Players() {
			global $db;
			global $boardplayers;
			global $board;
			global $users;
			
			if ( $this->mPlayers === false ) {
				$sql = "SELECT
							`user_id`, `user_name`, `user_icon`,
							`user_lastprofedit`, `bp_ready`, `board_gameid`,
							`bp_boardid`
						FROM
							(`$boardplayers` CROSS JOIN `$board`
								ON `bp_boardid` = `board_id`) 
							CROSS JOIN `$users`
								ON `user_id` = `bp_playerid`
						WHERE
							`board_id`='" . $this->BoardId() . "'
							AND `bp_lastactive` + INTERVAL 1 MINUTE > NOW()";
				$res = $db->Query( $sql );
				$this->mPlayers = array();
				while ( $row = $res->FetchArray() ) {
					$this->mPlayers[ $row[ 'bp_playerid' ] ] = New Player( $row );
				}
				$this->mPlayers = $players;
			}
			return $this->mPlayers;
		}
		public function Board( $boardid ) {
			global $game_boards;
			global $games;
			global $users;
			global $db;
			global $water;
			
			$this->mBoardId = 0;

			w_assert( is_numeric( $boardid ) );
			$sql = "SELECT
						`game_id`, `game_name`, `game_maxplayers`, `game_minplayers`
						`board_id`,
						`board_created`, `board_creatorid`, 
						`board_name`, `board_turn`,
						`board_data`, 
						`board_inviteonly`, `board_active`, `board_started`,
						`board_creatorid`
					FROM
						`$game_boards` CROSS JOIN `$games`
							ON `board_gameid` = `game_id`
					WHERE
						`board_id`='$boardid'
					LIMIT 1;";
			$res = $db->Query( $sql );
			if ( !$res->Results() ) {
				$water->Notice( 'Board not found' );
				return;
			}
			$row = $res->FetchArray();
			$this->mBoardId = isset( $row[ 'board_id' ] ) ? $row[ 'board_id' ] : 0;
			$this->mBoardName = isset( $row[ 'board_name' ] ) ? $row[ 'board_name' ] : 'Board';
			$this->mTurn = isset( $row[ 'board_turn' ] ) ? $row[ 'board_turn' ] : $this->mCreatorId;
			$this->mBoardInviteOnly = isset( $row[ 'board_inviteonly' ] ) && $row[ 'board_inviteonly' ] == 'yes';
			$this->mBoardActive = isset( $row[ 'board_active' ] ) && $row[ 'board_active' ] == 'yes';
			$this->mBoardStarted = isset( $row[ 'board_started' ] ) && $row[ 'board_started' ];
			$this->mCreatorId = isset( $row[ 'board_creatorid' ] ) ? $row[ 'board_creatorid' ] : 0;
			$this->mCreator = false;
			$this->mPlayers = false;
			
			// populate game information
			$this->Game( $row );
		}
	}
	
	class Player extends User {
		private $mReady;
		private $mGameId;
		private $mBoardId;
		
		public function GameId() {
			return $this->mGameId;
		}
		public function BoardId() {
			return $this->mBoardId;
		}
		public function MakeReady() {
			global $db;
			
			if ( !$this->mReady ) {
				$sql = "UPDATE
							`$boardplayers`
						SET
							`bp_ready`='yes'
						WHERE
							`bp_boardid`='" . $this->BoardId() . "'
							AND `bp_playerid`='" . $this->Id() . "'
						LIMIT 1;";
				$db->Query( $sql );
				$this->mReady = true;
				return true;
			}
			return false;
		}
		public function MakeNotReady() {
			global $db;
			global $boardplayers;
			
			if ( $this->mReady ) {
				$sql = "UPDATE
							`$boardplayers`
						SET
							`bp_ready`='no'
						WHERE
							`bp_boardid`='" . $this->BoardId() . "'
							AND `bp_playerid`='" . $this->Id() . "'
						LIMIT 1;";
				$db->Query( $sql );
				$this->mReady = false;
				return true;
			}
			return false;
		}
		public function IsReady() {
			return $this->mReady;
		}
		public function UpdateLastActive() {
			global $db;
			global $boardplayers;
			
			$sql = "UPDATE
						`$boardplayers`
					SET
						`bp_lastactive` = NOW()
					WHERE 
						`bp_playerid` = '" . $this->Id() . "'
					LIMIT 1;";
			$db->Query( $sql );
		}
		public function Player( $construct ) {
			w_assert( is_array( $construct ) );
			
			$this->mGameId = isset( $construct[ 'board_gameid' ] ) ? $construct[ 'board_gameid' ] : 0;
			$this->mReady = isset( $construct[ 'bp_ready' ] ) && $construct[ 'bp_ready' ] == 'yes';
			$this->mBoardId = isset( $construct[ 'bp_boardid' ] ) && $construct[ 'bp_boardid' ] : 0;
			$this->User( $construct );
		}
	}
	
	// get the last date a specific game was played by a given player
	// (e.g. last time dionyziz played chess)
	/*
	$sql = "SELECT
				`bp_lastactive`
			FROM
				`boards` CROSS JOIN `boardplayers` ON
					`board_id` = `bp_boardid`
			WHERE
				`bp_playerid` = '$userid'
				AND `bp_gameid` = '$gameid'
			ORDER BY
				`bp_lastactive` DESC
			LIMIT 1;"
	*/
?>
