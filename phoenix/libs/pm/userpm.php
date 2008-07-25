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
                    LEFT JOIN :pmmessages ON
                        `pmif_pmid` = `pm_id`
                    LEFT JOIN :users ON
                        `pmfolder_userid` = `user_id`
                WHERE
                    `pmif_pmid` = :pmid AND
                    `pmfolder_userid` != `pm_senderid`
                LIMIT
                    :offset, :limit;' );
            
            $query->BindTable( 'pmmessageinfolder', 'pmfolders', 'pmmessages', 'users' );
            $query->Bind( 'pmid', $pm->Id );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            return $query->Execute()->ToObjectsArray( 'User' );
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
            return $this->PM->Senderid == $user->Id;
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
        public function __get( $key ) {
            switch ( $key ) {
                case 'Sender':
                case 'Text':
                case 'Receivers':
                case 'Created':
                    return $this->PM->$key;
                case 'User':
                    return $this->Folder->User;
                default:
                    return parent::__get( $key );
            }
        }
        protected function Relations() {
            $this->PM = $this->HasOne( 'PM', 'Pmid' );
            $this->Folder = $this->HasOne( 'PMFolder', 'Folderid' );
        }
    }

?>
