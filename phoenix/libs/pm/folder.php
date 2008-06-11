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
            /*
            $prototype = New PMFolder();
            $prototype->Userid = $user->Id;
            $prototype->Typeid = $typeid;
            $prototype->Delid = 0;

            return $this->FindByPrototype( $prototype, $offset, $limit );
            */

            $query = $this->mDb->Prepare( '
                SELECT
                    *
                FROM
                    :pmfolders
                WHERE
                    `pmfolder_userid` = :userid AND
                    `pmfolder_delid` = 0 AND
                    `pmfolder_typeid` = :typeid
                LIMIT
                    :offset, :limit
                ;' );

            $query->BindTable( 'pmfolders' );
            $query->Bind( 'userid', $user->Id );
            $query->Bind( 'typeid', $typeid );
            $query->Bind( 'offset', $offset );
            $query->Bind( 'limit', $limit );

            $res = $query->Execute();
            w_assert( $res->Results(), 'No results for FindByUSerAndType' );

            $row = $res->FetchArray();
            w_assert( is_array( $row ) );

            $folder = New PMFolder( $row );
            return $folder;
        }
    }

    class PMFolder extends Satori {
        protected $mDbTableAlias = 'pmfolders';

        protected function Relations() {
            $this->User = $this->HasOne( 'User', 'Userid' );
            $this->UserPMs = $this->HasMany( 'UserPMFinder', 'FindByFolder', $this );
        }
        protected function OnBeforeDelete() {
            $this->Delid = 1;
            $this->Save();

            return false;
        }
        protected function LoadDefaults() {
            global $user;

            $this->Userid = $user->Id;
            $this->Delid = 0;
        }
    }

?>
