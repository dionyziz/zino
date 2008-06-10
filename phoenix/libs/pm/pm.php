<?php

    global $libs;
    $libs->Load( 'pm/userpm' );
    $libs->Load( 'pm/folder' );

    class PM extends Satori {
        protected $mDbTableAlias = 'pmmessages';
        protected $mReceivers;

        protected function SetReceivers( $receivers ) {
            $this->mReceivers = $receivers;
        }
        protected function AddReceiver( $receiver ) {
            $this->mReceivers[] = $receiver;
        }
        protected function GetReceivers() {
            if ( $this->Exists() && empty( $this->mReceivers ) ) {
                foreach ( $this->UserPMs as $upm ) {
                    $this->mReceivers[] = $upm->User;
                }
            }
            return $this->mReceivers;
        }
        protected function SetText( $text ) {
            $this->Bulk->Text = $text;
        }
        protected function GetText( $text ) {
            return $this->Bulk->Text;
        }
        protected function OnBeforeCreate() {
            $id = $this->Bulk->Save();
            $this->Bulkid = $id;
        }
        protected function OnCreate() {
            $ffinder = New PMFolderFinder();
            $receiversinbox = $ffinder->FindUsersInbox( $this->Receivers );
            foreach ( $this->Receivers as $receiver ) {
                $upm = New UserPM();
                $upm->PMid = $this->Id;
                $upm->Folderid = $receiversinbox[ $receiver->Id ];
                $upm->Delid = PM_UNREAD;
                $upm->Save();
            }
            
            $senderoutbox = $ffinder->FindUserOutbox( $this->Sender );
            $upm = New UserPM();
            $upm->PMid = $this->Id;
            $upm->Folderid = $senderoutbox->Id;
            $upm->Delid = PM_READ;
            $upm->Save();
        }
        protected function OnBeforeUpdate() {
            $this->Bulk->Save();
        }
        protected function Relations() {
            $this->Sender = $this->HasOne( 'User', 'SenderId' );
            $this->Bulk = $this->HasOne( 'Bulk', 'BulkId' );
        }
        protected function OnConstruct() {
            $this->mReceivers = array();
        }
        protected function LoadDefaults() {
            global $user;

            $this->Date = NowDate();
            $this->Senderid = $user->Id;
        }
    }

?>
