<?php

    global $libs;
    $libs->Load( 'pm/userpm' );
    $libs->Load( 'pm/folder' );

    class PM extends Satori {
        protected $mDbTableAlias = 'pmmessages';
        protected $mReceivers;

        public function OnDelete() {
            foreach ( $this->UserPMs as $upm ) {
                $upm->Delete();
            }
        }
        public function SetReceivers( $receivers ) {
            $this->mReceivers = $receivers;
        }
        public function AddReceiver( $receiver ) {
            $this->mReceivers[] = $receiver;
        }
        public function GetReceivers() {
            if ( $this->Exists() && empty( $this->mReceivers ) ) {
                $finder = New PMFinder();
                $this->mReceivers = $finder->FindReceivers( $this );
            }

            return $this->mReceivers;
        }
        public function SetText( $text ) {
            $this->Bulk->Text = $text;
        }
        public function GetText() {
            return $this->Bulk->Text;
        }
        protected function OnBeforeCreate() {
            $this->Bulk->Save();
            $this->Bulkid = $this->Bulk->Id;
        }
        protected function OnCreate() {
            global $water;

            $ffinder = New PMFolderFinder();
            $receiversinbox = $ffinder->FindUsersInbox( $this->Receivers );
            $water->Trace( "pm receivers", $this->Receivers );
            $water->Trace( "pm inbox", $receiversinbox );
            foreach ( $this->Receivers as $receiver ) {
                $upm = New UserPM();
                $upm->Pmid = $this->Id;
                $upm->Folderid = $receiversinbox[ $receiver->Id ]->Id;
                $upm->Delid = USERPM_UNREAD;
                $upm->Save();

                ++$receiver->Count->Unreadpms;
                $receiver->Count->Save();
            }
            
            w_assert( is_object( $this->Sender ), 'sender is not an object' );
            $senderoutbox = $ffinder->FindByUserAndType( $this->Sender, PMFOLDER_OUTBOX );
            w_assert( is_object( $senderoutbox ), 'sender outbox is not an object' );
            w_assert( $senderoutbox->Typeid == PMFOLDER_OUTBOX, 'sender outbox is not an outbox! D:' );
            $upm = New UserPM();
            $upm->Pmid = $this->Id;
            $upm->Folderid = $senderoutbox->Id;
            $upm->Delid = USERPM_READ;
            $upm->Save();
        }
        protected function OnBeforeUpdate() {
            $this->Bulk->Save();
        }
        protected function Relations() {
            $this->Sender = $this->HasOne( 'User', 'Senderid' );
            $this->Bulk = $this->HasOne( 'Bulk', 'Bulkid' );
            $this->UserPMs = $this->HasMany( 'PMFinder', 'FindByPM', $this );
        }
        protected function OnConstruct() {
            $this->mReceivers = array();
        }
        protected function GetSince() {
            return dateDistance( $this->Created );
        }
        protected function LoadDefaults() {
            global $user;

            $this->Created = NowDate();
            $this->Senderid = $user->Id;
        }
    }

?>
