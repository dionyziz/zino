<?php
    
    define( 'USERPM_READ', 1 );
    define( 'USERPM_DELETED', 2 );

    class UserPMFinder extends Finder {
        protected $mModel = 'UserPM';

        public function FindByFolder( $folder, $offset = 0, $limit = 1000 ) {
            $prototype = New UserPM();
            $prototype->Folderid = $folder->Id;
            $prototype->Delid = 

            $query = $this->mDb->Prepare( '
                SELECT
                    *
                FROM
                    :pmmessageinfolder
                WHERE
                    `pmif_folderid` = :folderid AND
                    `pmif_delid` < :deleteid
                LIMIT
                    :offset, :limit
                ;' );

            $query->BindTable( 'pmmessageinfolder' );
            $query->Bind( 'deleteid', USERPM_DELETED );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            return $this->FindBySqlResource( $query->Execute() );
        }
    }

    class UserPM extends Satori {
        protected $mDbTableAlias = 'pmmessageinfolder';

        public function IsRead() {
            return $this->Delid == USERPM_READ;
        }
        public function IsDeleted() {
            return $this->Delid == USERPM_DELETED;
        }
        public function Read() {
            $this->Delid = USERPM_READ;
            $this->Save();
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
        public function GetDate() {
            return $this->PM->Date;
        }
        protected function Relations() {
            $this->PM = $this->HasOne( 'PM', 'PMid' );
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->Folder = $this->HasOne( 'Folder', 'Folderid' );
        }
    }

?>
