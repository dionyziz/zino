<?php

    global $libs;
    $libs->Load( 'pm/userpm' );
    $libs->Load( 'pm/folder' );

    class PM extends Satori {
        protected $mDbTableAlias = 'pmmessages';
        protected $mReceivers;
        private $mText = false;

        public function __get( $key ) {
            switch ( $key ) {
                case 'Receivers':
                    if ( $this->Exists() && empty( $this->mReceivers ) ) {
                        $finder = New PMFinder();
                        $this->mReceivers = $finder->FindReceivers( $this );
                    }

                    return $this->mReceivers;
                case 'Text':
                    if ( $this->mText === false ) {
                        $this->mText = Bulk::FindById( $this->Bulkid );
                    }
                    return $this->mText;
                default:
                    return parent::__get( $key );
            }
        }
        public function __set( $key, $value ) {
            switch ( $key ) {
                case 'Receivers':
                    $this->mReceivers = $value;
                    break;
                case 'Text':
                    $this->mText = $value;
                    break;
                default:
                    parent::__set( $key, $value );
            }
        }
        public function OnDelete() {
            foreach ( $this->UserPMs as $upm ) {
                $upm->Delete();
            }
        }
        public function AddReceiver( $receiver ) {
            $this->mReceivers[] = $receiver;
        }
        protected function OnBeforeCreate() {
            $this->Bulkid = Bulk::Store( $this->mText );
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
            Bulk::Store( $this->mText, $this->Bulkid );
        }
        protected function Relations() {
            $this->Sender = $this->HasOne( 'User', 'Senderid' );
            $this->UserPMs = $this->HasMany( 'PMFinder', 'FindByPM', $this );
        }
        protected function OnConstruct() {
            $this->mReceivers = array();
        }
        protected function LoadDefaults() {
            global $user;

            $this->Created = NowDate();
            $this->Senderid = $user->Id;
        }
    }

?>
