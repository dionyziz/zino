<?php
   
    define( 'USERPM_UNREAD', 0 );
    define( 'USERPM_READ', 1 );
    define( 'USERPM_DELETED', 2 );
    
    class PMFinder extends Finder { /* aka UserPMFinder */
        protected $mModel = 'UserPM';

        public function FindReceivers( PM $pm, $offset = 0, $limit = 20 ) {
            $query = $this->mDb->Prepare( '
                SELECT
                    *
                FROM
                    :pmmessageinfolder 
                    LEFT JOIN :pmfolders ON
                        `pmif_folderid` = `pmfolder_id`
                    LEFT JOIN :users ON
                        `pmif_userid` = `user_id`
                WHERE
                    `pmif_pmid` = :pmid AND
                    `pmfolder_typeid` != :typeid
                LIMIT
                    :offset, :limit;' );
            
            $query->BindTable( 'pmmessageinfolder', 'pmfolders', 'users' );
            $query->Bind( 'pmid', $pm->Id );
            $query->Bind( 'typeid', 'outbox' );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            return $this->FindBySqlResource( $query->Execute() );
        }
        public function FindByPM( PM $pm, $offset = 0, $limit = 1000 ) {
            $prototype = New UserPM();
            $prototype->Pmid = $pm->Id;

            return $this->FindByPrototype( $prototype, $offset, $limit );
        }
        public function FindByFolder( PMFolder $folder, $offset = 0, $limit = 1000 ) {
            $query = $this->mDb->Prepare( '
                SELECT
                    *
                FROM
                    :pmmessageinfolder
                WHERE
                    `pmif_folderid` = :folderid AND
                    `pmif_delid` < :deleteid
                ORDER BY
                    `pmif_pmid` DESC
                LIMIT
                    :offset, :limit
                ;' );

            $query->BindTable( 'pmmessageinfolder' );
            $query->Bind( 'folderid', $folder->Id );
            $query->Bind( 'deleteid', USERPM_DELETED );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            return $this->FindBySqlResource( $query->Execute() );
        }
    }

    class UserPM extends Satori {
        protected $mDbTableAlias = 'pmmessageinfolder';

        public function IsSender( User $user ) {
            return $this->PM->Senderid = $user->Id;
        }
        public function IsRead() {
            return $this->Delid == USERPM_READ;
        }
        public function IsDeleted() {
            return $this->Delid == USERPM_DELETED;
        }
        public function Read() {
            $this->Delid = USERPM_READ;
            $this->Save();

            --$this->User->Count->Unreadpms;
            $this->User->Count->Save();
        }
        protected function BeforeDelete() {
            $this->Delid = USERPM_DELETED;
            $this->Save();

            return false;
        }
        public function GetSender() {
            return $this->PM->Sender;
        }
        public function GetText() {
            return $this->PM->Text;
        }
        public function GetReceivers() {
            return $this->PM->Receivers;
        }
        public function GetUser() {
            return $this->Folder->User;
        }
        public function GetCreated() {
            return $this->PM->Created;
        }
        public function GetSince() {
            return $this->PM->Since;
        }
        protected function Relations() {
            $this->PM = $this->HasOne( 'PM', 'Pmid' );
            $this->Folder = $this->HasOne( 'PMFolder', 'Folderid' );
        }
    }

?>
