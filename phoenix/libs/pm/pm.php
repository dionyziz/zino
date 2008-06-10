<?php

    global $libs;
    $libs->Load( 'pm/userpm' );
    $libs->Load( 'pm/folder' );

    class PM extends Satori {
        protected $mDbTableAlias = 'pmmessages';
        protected $mReceivers;

        public function SetReceivers( $receivers ) {
            $this->mReceivers = $receivers;
        }
        public function AddReceiver( $receiver ) {
            $this->mReceivers[] = $receiver;
        }
        public function GetReceivers() {
            if ( $this->Exists() && empty( $this->mReceivers ) ) {
                foreach ( $this->UserPMs as $upm ) {
                    $this->mReceivers[] = $upm->User;
                }
            }
            return $this->mReceivers;
        }
        public function SetText( $text ) {
            $this->Bulk->Text = $text;
        }
        public function GetText( $text ) {
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
                $upm->Pmid = $this->Id;
                $upm->Folderid = $receiversinbox[ $receiver->Id ];
                $upm->Delid = USERPM_UNREAD;
                $upm->Save();
            }
            
            $senderoutbox = $ffinder->FindUserOutbox( $this->Sender );
            $upm = New UserPM();
            $upm->Pmid = $this->Id;
            $upm->Folderid = $senderoutbox->Id;
            $upm->Delid = PM_READ;
            $upm->Save();
        }
        protected function OnBeforeUpdate() {
            $this->Bulk->Save();
        }
        protected function Relations() {
            $this->Sender = $this->HasOne( 'User', 'Senderid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
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
