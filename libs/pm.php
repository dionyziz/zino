<?php

    /* 
        Developer: Abresas 

        Library for Personal Messages. (Unstable)
    */

	define( 'PM_MAX_RECEIVERS', 10 );

	function PM_UserFolders() {
		global $pmfolders;
		global $user;
		global $db;
		
		$sql = "SELECT
					*
				FROM
					`$pmfolders`
				WHERE
					`pmfolder_userid` = '" . $user->Id() . "' AND
					`pmfolder_delid` = '0'
				;";
		
		$res = $db->Query( $sql );
		
		$ret = array();
		
		while ( $row = $res->FetchArray() ) {
			$ret[] = new PMFolder( $row );
		}
		
		return $ret;
	}
	
	function PM_UserCountUnreadPms( $theuser = '' ) {
			global $db;
			global $pmmessages;
			global $pmmessageinfolder;
			global $user;
			
			if ( empty( $theuser ) ) {
				$theuser = $user;
			}
			
			$sql = "SELECT
						COUNT( * ) AS unreadpms
					FROM
						`$pmmessages` RIGHT JOIN `$pmmessageinfolder` ON
							`pm_id` = `pmif_id`
					WHERE
						`pm_senderid` != '" . $theuser->Id() . "' AND
						`pmif_userid` = '" . $theuser->Id() . "' AND
						`pmif_delid` = '0'
					;";
			
			$fetched = $db->Query( $sql )->FetchArray();
			
			return $fetched[ "unreadpms" ];
	}
	
	final class PM extends Satori {
		protected $mId;
		protected $mDate;
		protected $mText;
		protected $mTextFormatted;
		protected $mSender;
		protected $mSenderId;
		protected $mReceivers;
		protected $mFolder;
		protected $mFolderId;
		protected $mDelId;
		protected $mUser;
        protected $mUserId;
		protected $mDbTable;
		protected $mDb;
		
		public function IsRead() {
			return $this->DelId >= 1;
		}
        protected function SetText( $value ) {
            $this->mTextFormatted = array_shift( mformatpms( array( $value ) ) );
            $this->mText = $value;
            return true;
        }
		protected function GetSender() {
			if ( $this->mSender === false ) {
				if ( $this->UserIsSender() ) {
					$this->mSender = $this->User;
				}
				else {
					$this->mSender = new User( $this->SenderId );
				}
			}
			return $this->mSender;
		}
		protected function GetFolder() {
			if ( $this->mFolder === false ) {
				$this->mFolder = PMFolder_Factory( $this->FolderId );
			}
			return $this->mFolder;
		}
		protected function GetReceivers() {
			if ( $this->mReceivers === false ) {
				$sql = "SELECT 
							`$users`.*
						FROM 
							`$pmmessageinfolder` INNER JOIN `$users` 
								ON `pmif_userid` = `user_id`
						WHERE
							`pmif_id` = '" . $this->Id . "' AND
							`pmif_userid` != '" . $this->SenderId . "' AND
							`user_delid` != 0
						LIMIT
							" . PM_MAX_RECEIVERS . "
						;";
				
				$res = $db->Query( $sql );
				
				$this->mReceivers = array();
				while ( $row = $res->FetchArray() ) {
					$this->mReceivers[] = new User( $row );
				}
			}
			return $this->mReceivers;
		}
		protected function GetUser() {
			return $this->mUser;
		}
		protected function SetSender( $sender ) {
			if ( $sender instanceof User ) {
				$this->mSender = $sender;
				$this->mSenderId = $sender->Id();
				return true;
			}
			else {
				$this->mSender = false;
			}
		}
		protected function SetSenderId( $senderid ) {
			$this->mSenderId = $senderid;
		}
		protected function SetFolder( $folder ) {
			if ( $folder instanceof PMFolder ) {
				$this->mFolder = $folder;
				$this->mFolderId = $folder->Id;
				
				return true;
			}
		}
		protected function SetFolderId( $folderid ) {
			$this->mFolderId = $folderid;
		}
		protected function SetReceivers( $receivers ) {
            $this->mReceivers = $receivers;
		}
		protected function SetUser( $pmuser ) {
			w_assert( $pmuser instanceof User );
			
			$this->mUser = $pmuser;
		}
		public function UserIsSender() {
			global $user;
			
			return $user->Id() == $this->SenderId;
		}
		public function AddReceiver( $receiver ) {
            global $water;
            
			if ( count( $this->mReceivers ) >= PM_MAX_RECEIVERS ) {
                $water->Notice( 'Max receivers exceeded for message!' );
				return false;
			}
			if ( !is_object( $receiver ) ) {
				$receiver = new User( $receiver );
			}
			$this->mReceivers[] = $receiver;
		}
		public function Save() {
			global $pmmessages;
			global $pmmessageinfolder;
			global $db;
			global $water;
            
			if ( $this->Exists() ) { // update
				$sql = "UPDATE
							`$pmmessages`, `$pmmessageinfolder`
						SET
							`pm_senderid` = '" . myescape( $this->SenderId ) . "',
							`pm_text` = '" . myescape( $this->Text ) . "',
							`pm_textformatted` = '" . myescape( $this->TextFormatted ) . "',
							`pmif_id` = '" . myescape( $this->Id ) . "',
							`pmif_userid` = '" . myescape( $this->UserId ) . "',
							`pmif_folderid` = '" . myescape( $this->FolderId ) . "',
							`pmif_delid` = '" . myescape( $this->DelId ) . "'
						WHERE
							`pm_id` = '" . $this->mPreviousValues[ 'mId' ] . "' AND
							`pmif_id` = `pm_id` AND
							`pmif_userid` = '" . $this->mPreviousValues[ 'mUserId' ] . "' AND
                            `pmif_folderid` = '" . $this->mPreviousValues[ 'mFolderId' ] . "'
						;";

                $this->mPreviousValues[ 'mId' ] = $this->Id;
                $this->mPreviousValues[ 'mUserId' ] = $this->UserId;
                $this->mPreviousValues[ 'mFolderId' ] = $this->FolderId;
				
				$change = $db->Query( $sql );
                
                return $change;
			}
            // else insert

			if ( empty( $this->Date ) ) {
				$this->Date = NowDate();
			}
            
            $sqlarray = array(
                'pm_senderid' => $this->SenderId,
                'pm_text' => $this->Text,
                'pm_textformatted' => $this->TextFormatted,
                'pm_date' => $this->Date
            );
            
            $change = $db->Insert( $sqlarray, $pmmessages );
            
            if ( $change === false ) {
                return false;
            }
            
            $this->mId = $change->InsertId();
            
            $inserts = array();
            
            $sqlarray = array(
                'pmif_id' => $this->mId, 
                'pmif_userid' => $this->mSenderId,
                'pmif_folderid' => -2,
                'pmif_delid' => 0
            );
            
            $inserts[] = $db->Insert( $sqlarray, $pmmessageinfolder );
            
            $sqlarrays = array();
            foreach ( $this->mReceivers as $receiver ) {
                $sqlarrays[] = array(
                    'pmif_id' => $this->mId, 
                    'pmif_userid' => $receiver->Id(),
                    'pmif_folderid' => -1,
                    'pmif_delid' => 0
                );
            }
            
            // client-check should've been performed
            w_assert( count( $sqlarrays ), 'No receipients specified for message!' );
            
            $change = $db->Insert( $sqlarrays, $pmmessageinfolder );
            
            return $change;
		}
		public function Delete() {
			$this->DelId = 2;
			
			return $this->Save();
		}
        protected function LoadDefaults() {
            $this->Date = NowDate();
        }
		public function PM( $construct = array(), $pmuser = false ) {
			global $user;
			global $db;
			global $pmmessages;
			global $pmmessageinfolder;
			global $pmfolders;
			
			if ( $pmuser === false ) {
				$pmuser = $user;
			}
			if ( !is_array( $construct ) ) {
				// find pm info
				$construct = myescape( $construct );
				
				$sql = "SELECT 
							* 
						FROM
							`$pmmessages` INNER JOIN 
							`$pmmessageinfolder` ON `pm_id` = `pmif_id` LEFT JOIN 
							`$pmfolders` ON `pmif_folderid` = `pmfolder_id` AND `pmif_userid` = `pmfolder_userid` AND `pmfolder_delid` = '0' 
						WHERE
							`pm_id` = '$construct' AND
							( `pm_senderid` = '" . $pmuser->Id() . "' OR `pmif_userid` = '" . $pmuser->Id() . "' ) 
						LIMIT
							1;";
				
				$res = $db->Query( $sql );
				if ( $res->Results() ) {
					$construct = $res->FetchArray();
				}
				else {
					$construct = array();
				}
			}
			
			$this->mDbTable = $pmmessages;
			$this->mDb = $db;
			
			$this->SetFields( array(
				'pm_id' => 'Id',
				'pm_text' => 'Text',
				'pm_textformatted' => 'TextFormatted',
				'pm_senderid' => 'SenderId',
				'pm_date' => 'Date',
				'pmif_folderid' => 'FolderId',
				'pmif_delid' => 'DelId',
                'pmif_userid' => 'UserId'
			) );
			
			$this->User			= $pmuser;

			$this->Sender 		= isset( $construct[ 'user_id' ] 		) ? New User( $construct ) 		: false;
			$this->Folder 		= isset( $construct[ 'pmfolder_id' ]	) ? New PMFolder( $construct ) 	: false;
			$this->Receivers 	= isset( $construct[ 'receivers' ] 		) ? $construct[ 'receivers' ] 	: false;
			
			$this->Satori( $construct );

            $this->mPreviousValues[ 'mId' ] = $this->Id;
            $this->mPreviousValues[ 'mUserId' ] = $this->UserId;
            $this->mPreviousValues[ 'mFolderId' ] = $this->FolderId;
		}
	}

	function PMFolder_Factory( $construct ) {
		switch ( $construct ) {
			case -1:
				return New PMInbox();
			case -2:
				return New PMOutbox();
			default:
				return New PMFolder( $construct );
		}
	}
	
	class PMFolder extends Satori {
		protected $mId;
		protected $mUserId;
		protected $mUser;
		protected $mName;
		protected $mDelId;
		
		public function User() {
			if ( $this->mUser == false ) {
				$this->mUser = new User( $this->mUserId );
			}
			return $this->mUser;
		}
		public function SetUser( $fuser ) {
			if ( $fuser instanceof User ) {
				$this->mUser = $fuser;
				$this->mUserId = $fuser->Id();
			}
		}
		public function Messages( $offset = false, $count = false ) {
			global $pmmessages;
			global $pmmessageinfolder;
			global $pmfolders;
			global $db;
			global $user;
			
			$limit = "";
			if ( $offset !==  false ) {
				$limit = "LIMIT $offset";
				if ( $count !== false ) {
					$limit .= ", $count";
				}
			}
			
			$sql = "SELECT
						*
					FROM
						`$pmmessageinfolder` INNER JOIN `$pmmessages` ON
							`pmif_id` = `pm_id`
					
					WHERE
						`pmif_folderid` = '" . $this->Id . "' AND
						`pmif_userid` = '" . $user->Id() . "' AND
						`pmif_delid` != '2'
					ORDER BY 
						`pm_id` DESC
					$limit
					;";
					
			$res = $db->Query( $sql );
			
			$ret = array();
			while ( $row = $res->FetchArray() ) {
				$ret[] = new PM( $row );
			}
			
			return $ret;
		}
		public function Delete() {
			global $pmfolders;
			global $db;
			global $user;
			
			$sql = "UPDATE `$pmfolders` SET `pmfolder_delid` = '1' WHERE `pmfolder_id` = '" . $this->Id . "' AND `pmfolder_userid` = '" . $user->Id() . "' LIMIT 1;";
			$change = $db->Query( $sql );
			
			return $change->Impact();
		}
		
		public function PMFolder( $construct ) {
			global $pmfolders;
			global $db;
			global $user;
			
			if ( !is_array( $construct ) ) {
				$sql = "SELECT * FROM `$pmfolders` WHERE `pmfolder_id` = '$construct' AND `pmfolder_userid` = '" . $user->Id() . "' LIMIT 1;";
				$res = $db->Query( $sql );
				
				$construct = $res->FetchArray();
			}
			
			$this->mDbTable = $pmfolders;
			$this->mDb = $db;
			
			$this->SetFields( array(
				'pmfolder_id' => 'Id',
				'pmfolder_userid' => 'UserId',
				'pmfolder_name' => 'Name',
				'pmfolder_delid' => 'DelId'
			) );
			
			$this->Satori( $construct );
			
			$this->mUser = isset( $construct[ "user_id" ] ) ? new User( $construct ) : false;
		}
	}
	
	class PMInbox extends PMFolder {
		public function Exists() {
			return true;
		}
		public function Delete() {
			w_die( "Tried to delete inbox folder" );
		}
		public function Save() {
			w_die( "Tried to update inbox folder" );
		}
		public function PMInbox( $name = '' ) {
			global $user;
			global $xc_settings;
			
			if ( empty( $name ) ) {
				$name = $xc_settings[ "pminboxname" ];
			}
			
			$construct = array( 
				'pmfolder_id' => -1,
				'pmfolder_userid' => $user->Id(),
				'pmfolder_name' => $name,
				'pmfolder_delid' => 0
			);
			
			$this->PMFolder( $construct );
		}
	}

	class PMOutbox extends PMFolder {
		public function Messages( $offset = false, $count = false ) {
			global $pmmessages;
			global $pmmessageinfolder;
			global $pmfolders;
			global $db;
			global $user;
			
			$limit = "";
			if ( $offset !==  false ) {
				$limit = "LIMIT $offset";
				if ( $count !== false ) {
					$limit .= ", $count";
				}
			}
			
			$sql = "SELECT
						*
					FROM
						`$pmmessageinfolder` INNER JOIN `$pmmessages` ON
							`pmif_id` = `pm_id`
					
					WHERE
						`pmif_folderid` = '" . $this->Id . "' AND
						`pm_senderid` = '" . $user->Id() . "' AND
						`pmif_delid` != '2'
					ORDER BY 
						`pm_id` DESC
					$limit
					;";
					
			$res = $db->Query( $sql );
			
			$ret = array();
			while ( $row = $res->FetchArray() ) {
				$ret[] = new PM( $row );
			}
			
			return $ret;
		}
		public function Exists() {
			return true;
		}
		public function Delete() {
			w_die( "Tried to delete outbox folder" );
		}
		public function Save() {
			w_die( "Tried to update outbox folder" );
		}
		public function PMOutbox( $name = '' ) {
			global $user;
			global $xc_settings;
			
			if ( empty( $name ) ) {
				$name = $xc_settings[ "pmoutboxname" ]; 
			}
			
			$construct = array( 
				'pmfolder_id' => -2,
				'pmfolder_userid' => $user->Id(),
				'pmfolder_name' => $name,
				'pmfolder_delid' => 0
			);
			
			$this->PMFolder( $construct );
		}
	}
	
?>
