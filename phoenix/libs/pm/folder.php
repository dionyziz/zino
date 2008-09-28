<?php

	define( 'PMFOLDER_INBOX', 'inbox' );
	define( 'PMFOLDER_OUTBOX', 'outbox' );
	define( 'PMFOLDER_USER', 'user' );

	function PMFolder_PrepareUser( $user ) {
		$inbox = New PMFolder();
		$inbox->Userid = $user->Id;
		$inbox->Typeid = PMFOLDER_INBOX;
		$inbox->Name = 'inbox';
		$inbox->Delid = 0;
		$inbox->Save();

		$outbox = New PMFolder();
		$outbox->Userid = $user->Id;
		$outbox->Typeid = PMFOLDER_OUTBOX;
		$outbox->Name = 'outbox';
		$outbox->Delid = 0;
		$outbox->Save();
	}

	class PMFolderFinder extends Finder {
		protected $mModel = 'PMFolder';

		public function FindUsersInbox( $users ) { /* array of user or user id */
			if ( !is_array( $users ) ) {
				$users = array( $users );
			}
			
			w_assert( !empty( $users ) );

			foreach ( $users as $i => $user ) {
				if ( is_object( $user ) ) {
					$users[ $i ] = $user->Id;
				}
			}

			$query = $this->mDb->Prepare( '
				SELECT
					*
				FROM
					:users LEFT JOIN :pmfolders
						ON `user_id` = `pmfolder_userid`
				WHERE
					`user_id` IN :pmusers AND
					`pmfolder_typeid` = :inboxtype
				;' );
	
			$query->BindTable( 'users' );
			$query->BindTable( 'pmfolders' );
			$query->Bind( 'pmusers', $users );
			$query->Bind( 'inboxtype', PMFOLDER_INBOX );

			$res = $query->Execute();
			$ret = array();
			while ( $row = $res->FetchArray() ) {
				$ret[ $row[ 'user_id' ] ] = New PMFolder( $row );
			}

			return $ret;
		}
		public function FindByUserAndType( $user, $typeid, $offset = 0, $limit = 100 ) {
			$prototype = New PMFolder();
			$prototype->Userid = $user->Id;
			$prototype->Typeid = $typeid;
			$prototype->Delid = 0;

			$ret = $this->FindByPrototype( $prototype, $offset, $limit, $order = array( 'Typeid', 'DESC' ) );
			if ( $typeid != PMFOLDER_USER ) { // unique
				return $ret[ 0 ];
			}
			return $ret;
		}
		public function FindByUser( $user, $offset = 0, $limit = 100 ) {
			$prototype = New PMFolder();
			$prototype->Userid = $user->Id;
			$prototype->Delid = 0;
			return $this->FindByPrototype( $prototype, $offset, $limit, array( 'Typeid', 'ASC' ) );
		}
	}

	class PMFolder extends Satori {
		protected $mDbTableAlias = 'pmfolders';

		protected function Relations() {
			$this->User = $this->HasOne( 'User', 'Userid' );
			$this->PMs = $this->HasMany( 'PMFinder', 'FindByFolder', $this );
		}
		protected function OnBeforeDelete() {
			$this->Delid = 1;
			$this->Save();

			return false;
		}
		public function IsDeleted() {
			return $this->Delid == 1;
		}
		protected function LoadDefaults() {
			global $user;

			$this->Userid = $user->Id;
			$this->Delid = 0;
			$this->Typeid = PMFOLDER_USER;
		}
	}

?>
